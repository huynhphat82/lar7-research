<?php

namespace App\Implementations;

use Illuminate\Queue\SqsQueue;

class SqsFifoQueue extends SqsQueue
{
    /**
     * pushRaw
     *
     * @param  mixed $payload
     * @param  mixed $queue
     * @param  mixed $options
     * @return void
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $response = $this->sqs->sendMessage([
            'QueueUrl' => $this->getQueue($queue),
            'MessageBody' => $payload,
            'MessageGroupId' => uniqid(),
            'MessageDeduplicationId' => uniqid(),
        ]);

        return $response->get('MessageId');
    }
}
