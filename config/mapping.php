<?php

return [
    /* <namespace of controller|any string> => [<path to validation file>, <namespace of this class>] */
    // default web requests path
    Constant::WEB_REQUEST_PATH_KEY => ['app/Http/Requests', 'App\Http\Requests'],
    // default api requests path
    Constant::API_REQUEST_PATH_KEY => ['app/Api/Requests', 'App\Api\Requests'],
    // mapping to controller
    \App\Http\Controllers::class => 'app/Validation/Requests',
    // mapping to method og controller
    \App\Http\Controllers\ApiController::class.'@testApi' => 'app/Validation/Requests/Apis'
];
