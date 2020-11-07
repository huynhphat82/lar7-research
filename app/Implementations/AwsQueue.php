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
     * Queue url
     *
     * @var string
     */
    private $url;

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
        $this->url = $this->client->getQueueUrl(['QueueName' => $this->name])->get('QueueUrl');
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
            return true;
        } catch (\Exception $e) {
            echo 'Error sending message to queue ' . $e->getMessage();
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

            if ($result['Messages'] == null) {
                // No message to process
                return false;
            }

            // Get the message and return it
            $result_message = array_pop($result['Messages']);
            return new Message($result_message['Body'], $result_message['ReceiptHandle']);
        } catch (\Exception $e) {
            echo 'Error receiving message from queue ' . $e->getMessage();
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
            return true;
        } catch (\Exception $e) {
            echo 'Error deleting message from queue ' . $e->getMessage();
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
            return true;
        } catch (\Exception $e) {
            echo 'Error releasing job back to queue ' . $e->getMessage();
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
            case 'send':
                return [
                    'QueueUrl' => $this->url,
                    'MessageBody' => $data,
                ];
            case 'receive':
                return [
                    'QueueUrl' => $this->url,
                ];
            case 'delete':
                return [
                    'QueueUrl' => $this->url,
                    'ReceiptHandle' => $data
                ];
            case 'release':
                return [
                    'QueueUrl' => $this->url,
                    'ReceiptHandle' => $data,
                    'VisibilityTimeout' => $timeout,
                ];
        }
        return [];
    }
}
