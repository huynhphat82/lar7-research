<?php

namespace App\Services\Facades;

use App\Services\AppLog as ServicesAppLog;
use Illuminate\Support\Facades\Facade;

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
 * @method static \App\Services\AppLog web(string $path)
 * @method static \App\Services\AppLog api(string $path)
 * @method static \App\Services\AppLog query()
 * @method static \App\Services\AppLog exception(Throwable $exception, bool $writeLog = true)
 *
 * @see \Illuminate\Log\Logger
 */
class AppLog extends Facade
{
    /**
     * @override
     *
     * getFacadeAccessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ServicesAppLog::class;
    }
}
