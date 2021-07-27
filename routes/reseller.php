<?php
/*
|--------------------------------------------------------------------------
| Reseller Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth','prefix' =>'reseller'], function () {
	//Dashboard
	Route::get('dashboard', 'Auth\AuthController@resellerlanding')->name('reseller.dashboard')->middleware(['permission:reseller.dashboard']);
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('logout', 'Auth\LoginController@logout')->name('logout');

	//User Management
	Route::get('/user/view', 'Reseller\UserController@getUserView')->name('reseller.user.view')->middleware(['permission:reseller.user.view']);
	Route::get('/user/create', 'Reseller\UserController@getUserCreate')->name('reseller.user.create')->middleware(['permission:reseller.user.create']);
	Route::post('/user/create', 'Reseller\UserController@postUserCreate')->name('reseller.user.create')->middleware(['permission:reseller.user.create']);
	
	//RESELLER Management
	Route::get('/user/reseller-create', 'Reseller\UserController@getResellerCreate')->name('reseller.user.reseller.create')->middleware(['permission:reseller.user.reseller.create']);
	Route::post('/user/reseller-create', 'Reseller\UserController@postResellerCreate')->name('reseller.user.reseller.create')->middleware(['permission:reseller.user.reseller.create']);
});