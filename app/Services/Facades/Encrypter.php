<?php

namespace App\Services\Facades;

use App\Services\EncryterService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\EncryterService encrypt(string $payload)
 * @method static \App\Services\EncryterService decrypt(string $encrypted)
 * @method static \App\Services\EncryterService verifyKey(string $key)
 *
 * @see \App\Services\EncryterService
 */
class Encrypter extends Facade
{
    /**
     * @override
     *
     * getFacadeAccessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return EncryterService::class;
    }
}
