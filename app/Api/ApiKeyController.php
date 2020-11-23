<?php

namespace App\Api;

use App\Api\ApiController;
use App\Services\Facades\Encrypter;

class ApiKeyController extends ApiController
{
    /**
     * Get X-API-KEY
     *
     * @param  mixed $payload
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiKey($payload = null)
    {
        return $this->responseSuccess(['key' => Encrypter::encrypt($payload)]);
    }

    /**
     * Verify X-API-KEY
     *
     * @param  string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyKey($key)
    {
        return $this->responseSuccess(['verified' => Encrypter::verifyKey($key)]);
    }
}
