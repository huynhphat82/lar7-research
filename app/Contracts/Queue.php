<?php

namespace App\Contracts;

use App\Implementations\Message;

interface Queue
{
    /**
     * Send message to queue
     *
     * @param  \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function send(Message $message);
    /**
     * Receive message from queue
     *
     * @return \App\Implementations\Message|bool
     * @throws \Exception
     */
    public function receive();
    /**
     * Delete message from queue
     *
     * @param \App\Implementations\Message $message
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(Message $message);
    /**
     * Delete message from queue
     *
     * @param \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function release(Message $message);
}
