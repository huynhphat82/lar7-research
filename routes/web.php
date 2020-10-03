<?php

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

Route::get('/', function () {
    return view('welcome');
});

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
