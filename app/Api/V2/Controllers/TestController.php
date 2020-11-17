<?php

namespace App\Api\V2\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->responseSuccess(['api' => "I'm from v2 api."]);
    }
}
