<?php

use App\Jobs\ProcessPodcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/s3', function () {
    $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'us-east-1',
        'endpoint' => 'http://s3:9000',
        'use_path_style_endpoint' => true,
        'credentials' => [
            'key'    => 'minioadmin',
            'secret' => 'minioadmin',
        ],
    ]);
    // Send a PutObject request and get the result object.
    $insert = $s3->putObject([
        'Bucket' => 'testing',
        'Key'    => 'testkey',
        'Body'   => 'Hello from MinIO!!'
    ]);

    dd($insert);

    // $s3->getObject([
    //     'Bucket' => 'testing',
    //     'Key' => 'Capture.PNG'
    // ]);

    // Storage::cloud()->put('hello.json', '{"hello": "world"}');
    // Storage::cloud()->get('Capture.PNG');
    // file_get_contents('http://s3:9000/testing/Capture.PNG');
});
