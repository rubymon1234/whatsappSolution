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
});
//default dashboard
Route::get('dashboard', 'Auth\AuthController@defaultlanding')->name('default.dashboard')->middleware(['permission:default.dashboard']);
// Errors
Route::get('/permission/denid/403', 'Web\HomeController@accessDenied')->name('accessDenied');