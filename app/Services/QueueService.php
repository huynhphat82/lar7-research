<?php

namespace App\Services;

use App\Jobs\PushNotification;

class QueueService
{
    private $queueConnection;
    private $queueName;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->queueConnection = env('QUEUE_CONNECTION');
        $this->queueName = env('SQS_QUEUE_FIFO');
    }

    /**
     * __callStatic
     *
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    /**
     * connect
     *
     * @param  mixed $queueConnection
     * @return void
     */
    protected function connect($queueConnection)
    {
        $this->queueConnection = $queueConnection;
        return $this;
    }

    /**
     * queue
     *
     * @param  mixed $queueName
     * @return void
     */
    protected function queue($queueName)
    {
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * push
     *
     * @param  mixed $message
     * @return void
     */
    protected function push($message)
    {
        dispatch(new PushNotification($message))
            ->onConnection($this->queueConnection)
            ->onQueue($this->queueName);
    }

}
