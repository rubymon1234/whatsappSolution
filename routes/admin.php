<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth','prefix' =>'admin'], function () {

	//Dashboard
	Route::get('dashboard', 'Auth\AuthController@homelanding')->name('admin.dashboard')->middleware(['permission:admin.dashboard']);
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
	Route::get('logout', 'Auth\LoginController@logout')->name('logout');

	// ACL - Roles and Permissions
	Route::get('/role/view', 'Acl\AclController@getViewRole')->name('acl.role.view')->middleware(['permission:acl.role.view']);
	Route::get('/role/create', 'Acl\AclController@getCreateRole')->name('acl.role.manage')->middleware(['permission:acl.role.create']);
	Route::post('/role/create', 'Acl\AclController@postCreateRole')->name('acl.role.manage')->middleware(['permission:acl.role.create']);
	Route::get('/role/action/{id}', 'Acl\AclController@getCreateRole')->name('acl.role.action')->middleware(['permission:acl.role.view']);
	Route::post('/role/action/{id}', 'Acl\AclController@postCreateRole')->name('acl.role.action')->middleware(['permission:acl.role.view']);

	//ASSIGN PERMISSION TO ROLES
	Route::get('role/assign/permissions/{id}', 'Acl\AclController@getPermissionAssign')->name('acl.assign.role.permission')->middleware(['permission:acl.assign.role.permission']);
	Route::post('role/assign/permissions/{id}', 'Acl\AclController@postPermissionAssign')->name('acl.assign.role.permission')->middleware(['permission:acl.assign.role.permission']);

	//Permission
	Route::get('/permission/view', 'Acl\AclController@getViewPerms')->name('acl.perms.view')->middleware(['permission:acl.perms.view']);
	Route::get('/permission/create', 'Acl\AclController@getCreatePerms')->name('acl.perms.manage')->middleware(['permission:acl.perms.create']);
	Route::post('/permission/create', 'Acl\AclController@postCreatePerms')->name('acl.perms.manage')->middleware(['permission:acl.perms.create']);
	Route::get('/permission/edit/{id}','Acl\AclController@getEditPerms')->name('acl.perms.edit')->middleware(['permission:acl.perms.view']);
	Route::post('/permission/edit/{id}','Acl\AclController@postEditPerms')->name('acl.perms.edit')->middleware(['permission:acl.perms.view']);
	Route::get('/permission/del/{id}','Acl\AclController@getDelPerms')->name('acl.perms.delete')->middleware(['permission:acl.perms.view']);

	//User Management
	Route::get('/user/view', 'Admin\UserController@getUserView')->name('admin.user.view')->middleware(['permission:admin.user.view']);
	Route::get('/user/create', 'Admin\UserController@getUserCreate')->name('admin.user.create')->middleware(['permission:admin.user.create']);
	Route::post('/user/create', 'Admin\UserController@postUserCreate')->name('admin.user.create')->middleware(['permission:admin.user.create']);
	
	//RESELLER Management
	Route::get('/user/reseller-create', 'Admin\UserController@getResellerCreate')->name('admin.user.reseller.create')->middleware(['permission:admin.user.reseller.create']);
	Route::post('/user/reseller-create', 'Admin\UserController@postResellerCreate')->name('admin.user.reseller.create')->middleware(['permission:admin.user.reseller.create']);
});