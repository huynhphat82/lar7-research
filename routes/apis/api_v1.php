<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([
    'VerifyAccessToken',
    'jwt.auth',
])->group(function () {
    Route::get('/test', '\App\Api\V1\Controllers\TestController@index');
    Route::post('request-messages', '\App\Api\V1\Controllers\TestController@requestMessages');
    Route::post('request-read-messages', '\App\Api\V1\Controllers\TestController@requestReadMessages');
});
