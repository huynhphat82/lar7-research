<?php

namespace App\Implementations;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;
use App\Implementations\SqsFifoQueue;
use Illuminate\Queue\Connectors\SqsConnector;

class SqsFifoConnector extends SqsConnector
{
    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @param  array   $options
     * @return mixed
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new SqsFifoQueue(
            new SqsClient($config), $config['queue'], Arr::get($config, 'prefix', '')
        );
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    // public function later($delay, $job, $data = '', $queue = null)
    // {
    //     $payload = $this->createPayload($job, $data);

    //     if (method_exists($this, 'getSeconds')) { // Support for Laravel < v5.4
    //         $delay = $this->getSeconds($delay);
    //     } else {
    //         $delay = $this->secondsUntil($delay);
    //     }

    //     return $this->sqs->sendMessage([
    //         'QueueUrl' => $this->getQueue($queue),
    //         'MessageBody' => $payload,
    //         'DelaySeconds' => $delay,
    //         'MessageGroupId' => uniqid(),
    //         'MessageDeduplicationId' => uniqid(),

    //     ])->get('MessageId');
    // }
}
