<?php
namespace App\Implementations;

use App\Contracts\HttpNewContract;

class HttpNew implements HttpNewContract
{
    public function request($config = [])
    {
        return 'HttpNew:request';
    }
}
