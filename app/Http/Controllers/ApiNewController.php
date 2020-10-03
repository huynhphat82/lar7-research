<?php

namespace App\Http\Controllers;

use App\Contracts\ApiContract;
use Illuminate\Http\Request;

class ApiNewController extends Controller
{
    private $api = null;

    public function __construct(ApiContract $api)
    {
        $this->api = $api;
    }

    public function testApi()
    {
        echo $this->api->request();
    }
}
