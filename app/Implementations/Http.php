<?php
namespace App\Implementations;

use App\Contracts\HttpContract;

class Http implements HttpContract
{
    public function request($config = [])
    {
        return 'Http:request';
    }
}
