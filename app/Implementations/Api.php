<?php
namespace App\Implementations;

use App\Contracts\ApiContract;
use App\Contracts\HttpContract;

class Api implements ApiContract
{
    private $http;

    public function __construct(HttpContract $http)
    {
        $this->http = $http;
    }

    public function request($config = [])
    {
        echo 'Api:request => '.$this->http->request();
    }
}
