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

	//SCRUB MANAGEMENT
	Route::get('/compose/scrub-view', 'User\ScrubController@getScrubView')->name('user.compose.scrub.view')->middleware(['permission:user.compose.scrub.view']);
	Route::get('/compose/scrub-create', 'User\ScrubController@getScrubCreate')->name('user.compose.scrub.create')->middleware(['permission:user.compose.scrub.create']);
	Route::post('/compose/scrub-create', 'User\ScrubController@postScrubCreate')->name('user.compose.scrub.create')->middleware(['permission:user.compose.scrub.create']);

	//MY PLAN
	Route::get('/my/plan', 'User\PlanController@getActivePlans')->name('user.plan.my.plans')->middleware(['permission:user.plan.my.plans']);
	Route::post('/my/plan', 'User\PlanController@postActivePlans')->name('user.plan.my.plans')->middleware(['permission:user.plan.my.plans']);

	//CAMPAIGN MANAGEMENT
	 Route::get('/message/campaign-list', 'User\CampaignController@getCampaignList')->name('user.campaign.view')->middleware(['permission:user.campaign.view']);

	//REPORT MANAGEMENT
	Route::get('/report/consolidated', 'User\ReportController@getConsolidatedReport')->name('user.report.consolidated')->middleware(['permission:user.report.consolidated']);

	//CHAT BOT - MESSAGE RESPONSES
	Route::get('/message/list-responses', 'User\ChatBot\MessageResponseController@getMessageResponse')->name('user.chat.bot.message.create')->middleware(['permission:user.chat.bot.message.create']);

	Route::post('/message/add-responses', 'User\ChatBot\MessageResponseController@addMessageResponse')->name('user.chat.bot.message.add');

	Route::get('/message/consolidated', 'User\ChatBot\MessageResponseController@listMessageResponse')->name('user.chat.bot.message.list');

	Route::get('/message/edit-response/{id}', 'User\ChatBot\MessageResponseController@getMessageResponseDetail')->name('user.chat.bot.message.edit');

	Route::get('/menu/add', 'User\MenuController@addMenuList')->name('user.menu.add');

	Route::get('/menu/list', 'User\MenuController@getMenuList')->name('user.menu.list');

	Route::post('/menu/save-update', 'User\MenuController@saveUpdate')->name('user.menu.saveUpdate');

});
Route::group(['middleware' => 'auth'], function () {
//PROFILE MANAGEMENT
Route::get('/my/profile', 'User\UserController@getProfileDetail')->name('global.my.profile')->middleware(['permission:global.my.profile']);
Route::post('/my/profile', 'User\UserController@postProfileDetail')->name('global.my.profile')->middleware(['permission:global.my.profile']);
//default dashboard
Route::get('dashboard', 'Auth\AuthController@defaultlanding')->name('default.dashboard')->middleware(['permission:default.dashboard']);

//globalAjaxcall
Route::post('/ajax/request-approve', 'Web\AjaxController@postRequestApprove')->name('ajax.request.approve')->middleware(['permission:ajax.request.approve']);
Route::post('/ajax/request-reject', 'Auth\AjaxController@postRequestReject')->name('ajax.request.reject')->middleware(['permission:ajax.request.reject']);
//user campaign cancel
Route::post('/ajax/cancel-campaign', 'Web\AjaxController@getCancelCampaign')->name('ajax.cancel.campaign');
Route::post('/ajax/block-user', 'Web\AjaxController@getBlockUser')->name('ajax.block.user');

// Message Request - AJax section starts

Route::post('/ajax/message-request/next-app-name', 'Web\AjaxController@getNextAppName')->name('ajax.message.request.appname');

// ends

Route::post('/ajax/current-recharge-status-change', 'Web\AjaxController@postCurrentStatus')->name('ajax.current.status.change');
});
//Errors
Route::get('/permission/denid/403', 'Web\HomeController@accessDenied')->name('accessDenied');