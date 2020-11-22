<?php

namespace App\Contracts;

interface Encrypter
{
    /**
     * Encrypt the given payload
     *
     * @param  mixed $payload
     * @return string
     */

    public function encrypt($payload = null);
    /**
     * Decrypt the encrypted string
     *
     * @param  string $encrypted
     * @return mixed
     */
    public function decrypt($encrypted = null);

    /**
     * Format string
     *
     * @param  mixed $payload
     * @return string
     */
    public function format($payload = null);
}
