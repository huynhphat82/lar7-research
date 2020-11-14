<?php

return [
    // <namespace of controller> => [<path to validation file>, <namespace of class in this file>]
    Constant::WEB_REQUEST_PATH_KEY => ['app/Http/Requests', 'App\Http\Requests'],
    Constant::API_REQUEST_PATH_KEY => ['app/Http/Requests/Apis', 'App\Http\Requests\Apis'],
    \App\Http\Controllers::class => 'app/Validation/Requests',
    \App\Http\Controllers\ApiController::class.'@testApi' => 'app/Validation/Requests/Apis'
];
