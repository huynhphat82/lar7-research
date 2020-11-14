<?php

return [
    // <namespace of controller> => [<path to validation file>, <namespace of class in this file>]
    'default_request_path' => ['app/Http/Requests', 'App\Http\Requests'],
    // 'App\Http\Controllers' => ['app/Validation', 'App\Validation'],
    \App\Http\Controllers::class => 'app/Validation',
    \App\Http\Requests::class => 'app/Http/Requests/Test',
    \App\Http\Controllers\ApiController::class.'@testApi' => 'app/Http/Requests/Api'
];
