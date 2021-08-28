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

	Route::post('/message/add-responses', 'User\ChatBot\MessageResponseController@addMessageResponse')->name('user.chat.bot.message.add')->middleware(['permission:user.chat.bot.message.create']);

	Route::get('/message/response-list', 'User\ChatBot\MessageResponseController@listMessageResponse')->name('user.chat.bot.message.list')->middleware(['permission:user.chat.bot.message.list']);

	Route::get('/message/edit-response/{id}', 'User\ChatBot\MessageResponseController@getMessageResponseDetail')->name('user.chat.bot.message.edit')->middleware(['permission:user.chat.bot.message.update']);

	Route::get('/menu/add', 'User\MenuController@addMenuList')->name('user.chat.bot.menu.create')->middleware(['permission:user.chat.bot.menu.create']);

	Route::get('/menu/list', 'User\MenuController@getMenuList')->name('user.chat.bot.menu.list')->middleware(['permission:user.chat.bot.menu.list']);

	Route::get('/menu/edit/{id}', 'User\MenuController@editMenuList')->name('user.menu.edit');

	Route::post('/menu/remove-key', 'User\MenuController@removeMenuKey')->name('user.menu.delete.key');

	Route::post('/menu/save-update', 'User\MenuController@saveUpdate')->name('user.chat.bot.menu.update')->middleware(['permission:user.chat.bot.menu.update']);

	//BOT INSTANCE
	Route::get('/message/instance-list', 'User\ChatBot\BotInstanceController@getInstanceList')->name('user.chat.bot.instance.list')->middleware(['permission:user.chat.bot.instance.list']);
	
	Route::get('/message/instance-create', 'User\ChatBot\BotInstanceController@getInstanceCreate')->name('user.chat.bot.instance.create')->middleware(['permission:user.chat.bot.instance.create']);
	Route::post('/message/instance-create', 'User\ChatBot\BotInstanceController@postInstanceCreate')->name('user.chat.bot.instance.create')->middleware(['permission:user.chat.bot.instance.create']);	


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