<?php

namespace App\Services;

use App\Services\Facades\AppLog;
use Monolog\Logger;
use Illuminate\Support\Str;
use App\Services\LogFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;

class LogHandler extends AbstractProcessingHandler
{
    /**
     * __construct
     *
     * @param  string $level
     * @return void
     */
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level);
    }

    /**
     * @abstract
     *
     * Write log to file
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record): void
    {
        $method = Str::lower($record['level_name']);
        $context = $record['context'] ?: [];
        $isApi = request()->is('api/*');
        if ($isApi) {
            AppLog::api()->{$method}($record['message'], $context);
        } else {
            AppLog::web()->{$method}($record['message'], $context);
        }
    }

    /**
     * @inheritDoc
     *
     * Format lines in log file
     *
     * @return Monolog\Formatter\FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogFormatter();
    }
}
