<?php

use Illuminate\Http\Request;

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
Route::post('incoming-message-capture','Api\Common\IncomingMessageCaptureController@IncomingMessageCaptureRequest')->name('IncomingMessageCaptureRequest');
Route::post('send', 'Api\ApiController@send')->name('send')->middleware(['apiauth']);


