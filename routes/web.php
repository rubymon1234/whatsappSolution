<?php

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
Route::post('/login', 'Auth\AuthController@login')->name('login');
Route::get('/login', 'Auth\AuthController@index')->name('login');
Route::get('/', 'Auth\AuthController@index')->name('base');

Route::group(['middleware' => 'auth','prefix' =>'user'], function () {
	//Dashboard
	Route::get('dashboard', 'Auth\AuthController@userlanding')->name('user.dashboard')->middleware(['permission:user.dashboard']);
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('logout', 'Auth\LoginController@logout')->name('logout');

	//PURCHASE HISTORY
	Route::get('/credit/transaction-list', 'User\PurchaseHistoryController@getPurchaseHistory')->name('user.recharge.transaction.view')->middleware(['permission:user.recharge.transaction.view']);

	//INSTANCE MANAGEMENT
	Route::get('/instance/list', 'User\InstanceController@getInstanceView')->name('user.instance.view')->middleware(['permission:user.instance.view']);
	Route::post('/instance/create', 'User\InstanceController@postInstanceCreate')->name('user.instance.create');
	Route::post('/ajax/scan-qr', 'Web\AjaxController@postQRScan')->name('user.instance.postqrscan')->middleware(['permission:user.instance.view']);

	//COMPOSE MANAGEMENT
	Route::get('/sent/message', 'User\ComposeController@getComposeView')->name('user.compose.sent.message')->middleware(['permission:user.compose.sent.message']);
	Route::post('/sent/message', 'User\ComposeController@postComposeView')->name('user.compose.sent.message')->middleware(['permission:user.compose.sent.message']);
});
//default dashboard
Route::get('dashboard', 'Auth\AuthController@defaultlanding')->name('default.dashboard')->middleware(['permission:default.dashboard']);

//globalAjaxcall
Route::post('/ajax/request-approve', 'Web\AjaxController@postRequestApprove')->name('ajax.request.approve')->middleware(['permission:ajax.request.approve']);
Route::post('/ajax/request-reject', 'Auth\AjaxController@postRequestReject')->name('ajax.request.reject')->middleware(['permission:ajax.request.reject']);

//Errors
Route::get('/permission/denid/403', 'Web\HomeController@accessDenied')->name('accessDenied');