<?php

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/view', function () {
    $data = [
        'title' => 'Child',
        'content' => 'Test content',
        'html' => '<b>This is html content</b>',
        'users' => [
            (object)[
                'name' => 'jhp',
                'age' => 30,
            ],
            (object)[
                'name' => 'jhphich',
                'age' => 35,
            ],
        ]
    ];
    return view('layouts.child', ['data' => $data]);
});

Route::get('/api/test', 'ApiController@testApi');
Route::get('/api/newtest', 'ApiNewController@testApi');

Route::get('/{page}', 'TestController')->name('page')->where('page', 'about|contact|terms');
Route::get('/download', 'TestController@download')->name('admin.download');
Route::get('/log', 'TestController@testLog')->name('admin.log');
Route::post('/sqs', 'TestController@testSQS')->name('admin.sqs');
Route::get('/test-validation', 'TestController@testValidation')->name('admin.test-validation');

Route::get('/sendmail', function () {
    $data = ['name' => 'Jhp Phich'];

    // Specify content with text format
    Mail::send(['text' => 'mail/mail'], $data, function (Message $message) {
        $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail (Text)');
        $message->from('xyz@gmail.com','Virat Gandhi');
    });

    // Sepecify content with html format
    Mail::send(['html' => 'mail/mail'], $data, function (Message $message) {
        $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail');
        $message->from('xyz@gmail.com','Virat Gandhi');
    });
    echo "Basic Email Sent. Check your inbox.";
});

Route::get('/cache', function () {
    // cache()->set('name', 'Jhp Phich');
    // echo cache()->get('name');
    echo env('CACHE_PREFIX')."<br>";
    Redis::set('name', 'Jhp Phich - '.date('Y-m-d H:i:s'));
    Redis::set('age', 40);
    Redis::set('sex', 'Male');
    Redis::set('edu', 'Master');
    echo Redis::get('name');

    Redis::publish('test-channel', json_encode([
        'name' => 'Adam Wathan - '.date('Y-m-d H:i:s')
    ]));
});
