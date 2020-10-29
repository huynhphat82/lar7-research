<?php

namespace App\Services;

use Monolog\Logger;
use App\Services\LogHandler;
use App\Services\LogProcessor;

class CustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger($this->_channel());
        $logger->pushHandler(new LogHandler());
        $logger->pushProcessor(new LogProcessor());
        return $logger;
    }

    /**
     * Get channel
     *
     * @return string
     */
    private function _channel()
    {
        return env('LOG_CHANNEL', 'custom');
    }
}
