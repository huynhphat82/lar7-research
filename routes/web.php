<?php

use App\Jobs\ProcessPodcast;
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
