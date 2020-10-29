<?php

namespace App\Services;

use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @method static \Psr\Log\LoggerInterface channel(string $channel = null)
 * @method static \Psr\Log\LoggerInterface stack(array $channels, string $channel = null)
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void emergency(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 *
 * @see \Illuminate\Log\Logger
 */
class AppLog
{
    private const WEB_CHANNEL = 'web';
    private const API_CHANNEL = 'api';
    private const LOGGING_WEB_FILENAME = 'admin.log';
    private const LOGGING_API_FILENAME = 'api.log';
    private const DEFAULT_LOG_FILE = 'logs/webadmin/admin.log';
    private const USE_POSTFIX_DYNAMICALLY = true;

    protected $channel = self::WEB_CHANNEL;
    protected $path = self::DEFAULT_LOG_FILE;

    /**
     * __call
     *
     * @param  string $method
     * @param  array $arguments
     * @return void
     */
    public function __call($method, $arguments)
    {
        $this->_validate();
        Log::channel($this->channel)->$method(...$arguments);
        $this->channel = self::WEB_CHANNEL;
        $this->path = $this->_webLogFilePathDefault();
    }

    /**
     * Set web channel
     *
     * @param  string $path
     * @return \AppLog
     */
    public function web($path = null)
    {
        return $this->_resolve(self::WEB_CHANNEL, $path);
    }

    /**
     * Set api channel
     *
     * @param  string $path
     * @return \AppLog
     */
    public function api($path = null)
    {
        return $this->_resolve(self::API_CHANNEL, $path);
    }

    /**
     * Get current log file path
     *
     * @return string
     */
    protected function getLogFilePath()
    {
        return storage_path($this->path);
    }

    /**
     * Check log file and channel whether they exist
     *
     * @return void
     * @throws \Exception
     */
    private function _validate()
    {
        // verify channel
        $channelTarget = "logging.channels.{$this->channel}";
        if (!config($channelTarget)) {
            throw new Exception("Log channel [{$this->channel}] not exist. Please define it in file 'config/logging.php'.");
        }
        $keyPath = "{$channelTarget}.path";
        $defaultPath = config($keyPath);
        // set name of log file dynamically if it is still as default filename
        if (self::USE_POSTFIX_DYNAMICALLY) {
            $filenameCurrent = current(array_slice(explode('/', $this->path), -1));
            if ($filenameCurrent == self::LOGGING_WEB_FILENAME) {
                $this->path = $this->_webLogFilePathDefault();
            } else if ($filenameCurrent == self::LOGGING_API_FILENAME) {
                $this->path = $this->_apiLogFilePathDefault();
            }
        }
        // verify log file & change default log file path
        $pathLogFile = $this->getLogFilePath();
        if (!file_exists($pathLogFile) || $defaultPath != $pathLogFile) {
            config()->set($keyPath, $pathLogFile);
        }
    }

    /**
     * Resolve parameters
     *
     * @param  string $channel
     * @param  string $path
     * @return \AppLog
     */
    private function _resolve($channel, $path = null)
    {
        $postfix = $this->_postfix(date('Y-m-d'));
        switch ($channel) {
            case self::WEB_CHANNEL:
                $this->path = $path ?: $this->_webLogFilePathDefault($postfix);
                break;
            case self::API_CHANNEL:
                $this->path = $path ?: $this->_apiLogFilePathDefault($postfix);
                break;
        }
        $this->channel = $channel;
        return $this;
    }

    /**
     * Default log file path for web
     *
     * @param  string $postfix
     * @return string
     */
    private function _webLogFilePathDefault($postfix = null)
    {
        return "logs/webadmin/admin_{$this->_postfix($postfix)}.log";
    }

    /**
     * Default log file path for api
     *
     * @param  string $postfix
     * @return string
     */
    private function _apiLogFilePathDefault($postfix = null)
    {
        return "logs/api/api_{$this->_postfix($postfix)}.log";
    }

    /**
     * Create postfix for log filename
     *
     * @param  string $postfix
     * @return string
     */
    private function _postfix($postfix = null)
    {
        return $postfix ?: date('Y-m-d');
    }

    /**
     * Log sql query
     *
     * @param callable|null $callback
     * @return void
     */
    public function query($callback = null)
    {
        $callback = is_callable($callback) ? $callback : function ($query) {
            try {
                if ($this->_filterQuery($query)) {
                    $this->info('Query => '.json_encode([
                        "DATABASE SERVER" => $query->connectionName,
                        "DATABASE NAME" => $query->connection->getconfig()['database'],
                        "QUERY TIME" => $query->time,
                        "EXECUTE SQL" => $query->sql,
                        "BINDINGS" => $query->bindings,
                    ], JSON_PRETTY_PRINT));
                }
            } catch (\Exception $e) {
                $this->error("Query Error => ".$e->getMessage());
            }
        };
        DB::listen($callback);
    }

    /**
     * Log exception
     *
     * @param  \Throwable $exception
     * @param  bool $writeLog
     * @return void|null
     */
    public function exception(Throwable $exception, bool $writeLog = true)
    {
        if (!$writeLog) {
            return;
        }
        $context = [
            'Class' => get_class($exception), // Get class đã gây ra lỗi
            'Code' => $exception->getCode(), // Get error code
            'File' => $exception->getFile(), // Lấy thông tin của file
            'Line' => $exception->getLine(), // Lấy thông tin của line lỗi
            'User' => auth()->user(), // Lấy thông tin của user gây lỗi
            'Url' => request()->getUri(), // Url gặp lỗi
            'Server' => request()->ip(), // Thông tin của server (ip)
            'Trace' => $exception->getTraceAsString() // Lấy stack trace
        ];
        $this->web()->emergency($exception->getMessage(). ' => '.json_encode($context, JSON_PRETTY_PRINT));
    }

    /**
     * _filterQuery
     *
     * @param  mixed $query
     * @return bool
     */
    private function _filterQuery($query)
    {
        return true;
    }
}
