<?php

namespace App\Services;

use App\Contracts\Encrypter;

class EncryterService
{
    /**
     * @var \App\Contracts\Encrypter
     */
    private $encrypter;

    /**
     * __construct
     *
     * @param  \App\Contracts\Encrypter $encrypter
     * @return void
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Encrypt payload
     *
     * @param  mixed $payload
     * @return string
     */
    public function encrypt($payload = null)
    {
        return $this->encrypter->encrypt($payload ?: request()->getHost());
    }

    /**
     * Decrypt the encrypted string
     *
     * @param  string $encrypted
     * @return mixed
     */
    public function decrypt($encrypted = null)
    {
        return $this->encrypter->decrypt($encrypted);
    }

    /**
     * Verify public key
     *
     * @param  string $key
     * @return bool
     */
    public function verifyKey($key = null)
    {
        $allowed = [
            request()->getHost(),
            request()->ip(),
        ];
        foreach ($allowed as $payload) {
            if ($this->decrypt($key) === $this->encrypter->format($payload)) {
                return true;
            }
        }
        return false;
    }
}
