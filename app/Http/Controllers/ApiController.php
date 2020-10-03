<?php

namespace App\Http\Controllers;

use App\Contracts\ApiContract;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $api = null;

    public function __construct(ApiContract $api, $primitives)
    {
        $this->api = $api;
        var_dump($primitives);
    }

    public function testApi()
    {
        echo $this->api->request();
    }
}
