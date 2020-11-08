<?php

namespace App\Implementations;

class Message
{
    /**
     * The receipt handle from SQS, used to identify the message when interacting with the queue
     *
     * @var string
     */
    public $receipt_handle;
    /**
     * Data
     *
     * @var array
     */
    public $data;

    /**
     * __construct
     *
     * @param string|array $data  [JSON String or an assoc array containing the message data]
     * @param string $receipt_handle  [The sqs receipt handle of the message]
     * @return void
     */
    public function __construct($data, $receipt_handle = '')
    {
        // If data is a json string, decode it into an assoc array
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        $this->data = $data;
        // Assign the data values and receipt handle to the object
        $this->receipt_handle = $receipt_handle;
    }

    /**
     * Returns the data of the message as a JSON string
     *
     * @return string  JSON message data
     */
    public function asJson()
    {
        return json_encode($this->data);
    }

    /**
     * Process data
     *
     * @return void
     */
    public function process()
    {
        // Process data here
        print_r('Message processed: '.$this->asJson());
        echo "\n";
    }
}
