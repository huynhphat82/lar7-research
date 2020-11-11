<?php

namespace App\Implementations;

use Aws\Sqs\SqsClient;
use App\Contracts\Queue;
use App\Implementations\Message;

class AwsQueue implements Queue
{
    /**
     * Sqs client
     *
     * @var \Aws\Sqs\SqsClient
     */
    private $client;
    /**
     * Queue name
     *
     * @var string
     */
    private $name;
    /**
     * Use queue fifo
     *
     * @var boolean
     */
    private $useFIFO = false;
    /**
     * SEND type as send() method name
     */
    private const SEND = 'send';
    /**
     * RECEIVE type as receive() method name
     */
    private const RECEIVE = 'receive';
    /**
     * DELETE type as delete() method name
     */
    private const DELETE = 'delete';
    /**
     * RELEASE type as release() method name
     */
    private const RELEASE = 'release';

    /**
     * __construct
     *
     * @param string $name
     * @param  \Aws\Sqs\SqsClient $client
     * @return void
     */
    public function __construct($name, SqsClient $client)
    {
        $this->client = $client;
        $this->name = $name;
    }

    /**
     * Get queue url
     *
     * @return string
     */
    private function getUrl()
    {
        $name = $this->useFIFO && env('SQS_QUEUE_FIFO') ? env('SQS_QUEUE_FIFO') : $this->name;
        return $this->client->getQueueUrl(['QueueName' => $name])->get('QueueUrl');
    }

    /**
     * Use Queue FIFO
     *
     * @return \AwsQueue
     */
    public function fifo()
    {
        $this->useFIFO = true;
        return $this;
    }

    /**
     * Send message to queue
     *
     * @param  \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function send(Message $message)
    {
        try {
            $this->client->sendMessage($this->_build(__FUNCTION__, $message->asJson()));
            $this->useFIFO = false;
            return true;
        } catch (\Exception $e) {
            echo 'Error sending message to queue ' . $e->getMessage();
            $this->useFIFO = false;
            return false;
        }
    }

    /**
     * Receive message from queue
     *
     * @return \App\Implementations\Message|bool
     * @throws \Exception
     */
    public function receive()
    {
        try {
            $result = $this->client->receiveMessage($this->_build(__FUNCTION__));
            $this->useFIFO = false;

            if ($result['Messages'] == null) {
                // No message to process
                return false;
            }

            // Get the message and return it
            $result_message = array_pop($result['Messages']);
            return new Message($result_message['Body'], $result_message['ReceiptHandle']);
        } catch (\Exception $e) {
            echo 'Error receiving message from queue ' . $e->getMessage();
            $this->useFIFO = false;
            return false;
        }
    }

    /**
     * Delete message from queue
     *
     * @param \App\Implementations\Message $message
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(Message $message)
    {
        try {
            $this->client->deleteMessage($this->_build(__FUNCTION__, $message->receipt_handle));
            $this->useFIFO = false;
            return true;
        } catch (\Exception $e) {
            echo 'Error deleting message from queue ' . $e->getMessage();
            $this->useFIFO = false;
            return false;
        }
    }

    /**
     * Delete message from queue
     *
     * @param \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function release(Message $message)
    {
        try {
            // Set the visibility timeout to 0 to make the message visible in the queue again straight away
            $this->client->changeMessageVisibility($this->_build(__FUNCTION__, $message->receipt_handle, 0));
            $this->useFIFO = false;
            return true;
        } catch (\Exception $e) {
            echo 'Error releasing job back to queue ' . $e->getMessage();
            $this->useFIFO = false;
            return false;
        }
    }

    /**
     * Build parameters
     *
     * @param  string $type
     * @param  string $data
     * @param  int $timeout
     * @return array
     */
    private function _build($type, $data = null, $timeout = 0)
    {
        // $args = func_get_args();

        // $type = $args[0];
        // $data = args[1] ?? null;
        // $timeout = args[2] ?? 0;

        switch ($type) {
            case self::SEND:
                return array_merge([
                    'QueueUrl' => $this->getUrl(),
                    'MessageBody' => $data,
                ], $this->useFIFO ? [
                    'MessageGroupId' => uniqid(),
                    'MessageDeduplicationId' => uniqid()
                ] : []);
            case self::RECEIVE:
                return [
                    'QueueUrl' => $this->getUrl(),
                ];
            case self::DELETE:
                return [
                    'QueueUrl' => $this->getUrl(),
                    'ReceiptHandle' => $data
                ];
            case self::RELEASE:
                return [
                    'QueueUrl' => $this->getUrl(),
                    'ReceiptHandle' => $data,
                    'VisibilityTimeout' => $timeout,
                ];
        }
        return [];
    }
}
