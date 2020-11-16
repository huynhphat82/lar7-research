<?php

namespace App\Http\Controllers;

use App\Contracts\ApiContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    private $api = null;

    public function __construct(ApiContract $api, $primitives)
    {
        $this->api = $api;
        // var_dump($primitives);
    }

    public function testApi()
    {
        Log::info(__FUNCTION__, [get_class($this) => __FUNCTION__]);
        echo $this->api->request();
    }
}
