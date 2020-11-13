<?php

namespace App\Contracts;

use App\Implementations\Message;

interface Sns
{
    /**
     * Send message to queue
     *
     * @param  \App\Implementations\Message $message
     * @return bool
     * @throws \Exception
     */
    public function notify(Message $message);
    public function subscribe();
}
