<?php

namespace App\Implementations;

use App\Contracts\Encrypter;
use Illuminate\Support\Facades\Crypt;

class EncrypterCrypt implements Encrypter
{
    /**
     * Secret private key
     *
     * @var string
     */
    private $secret = '1234567890123456789abcdefghijklmnopqrstuvwxyz';

    /**
     * Encrypt the given payload
     *
     * @param  mixed $payload
     * @return string
     */
    public function encrypt($payload = null)
    {
        return Crypt::encryptString($this->format($payload));
    }

    /**
     * Decrypt the encrypted string
     *
     * @param  string $encrypted
     * @return mixed
     */
    public function decrypt($encrypted = null)
    {
        return Crypt::decryptString($encrypted);
    }

    /**
     * Format the encrypted string
     *
     * @param  string $payload
     * @return string
     */
    public function format($payload = null)
    {
        return "{$this->secret}.{$payload}";
    }
}
