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
	Route::get('/instance/update/{id}', 'User\InstanceController@getInstanceUpdate')->name('user.instance.update')->middleware(['permission:user.instance.update']);
	Route::post('/instance/update/{id}', 'User\InstanceController@postInstanceUpdate')->name('user.instance.update')->middleware(['permission:user.instance.update']);


	//COMPOSE MANAGEMENT
	Route::get('/sent/message', 'User\ComposeController@getComposeView')->name('user.compose.sent.message')->middleware(['permission:user.compose.sent.message']);
	Route::post('/sent/message', 'User\ComposeController@postComposeView')->name('user.compose.sent.message')->middleware(['permission:user.compose.sent.message']);

	//INBOUND MESSAGES
	Route::get('/inbound/message', 'User\InboundMessagesController@getInboundView')->name('user.compose.inbound.message')->middleware(['permission:user.compose.inbound.message']);
	Route::post('/inbound/message', 'User\InboundMessagesController@getInboundView')->name('user.compose.inbound.message')->middleware(['permission:user.compose.inbound.message']);
	Route::post('/create/inbound-campaign', 'User\InboundMessagesController@postInBoundMessagePush')->name('ajax.create.inbound.campaign');


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
	Route::get('/report/log-sessions', 'User\ReportController@getChatBotReport')->name('user.report.log.sessions')->middleware(['permission:user.report.log.sessions']);
	Route::get('/report/user-input', 'User\ReportController@getMenuInputReport')->name('user.report.log.menu.input')->middleware(['permission:user.report.log.menu.input']);

	//GROUPS MANAGEMENT
	Route::get('/group/list', 'User\GroupsController@getListGroup')->name('user.group.view')->middleware(['permission:user.group.view']);
	Route::post('/group/create', 'User\GroupsController@getCreateGroup')->name('user.group.create')->middleware(['permission:user.group.view']);
	Route::get('/group/update/{id}', 'User\GroupsController@getUpdateGroup')->name('user.group.update')->middleware(['permission:user.group.view']);
	Route::post('/group/updatecsv/{id}', 'User\GroupsController@postUpdateCsvGroup')->name('user.group.update.csv')->middleware(['permission:user.group.view']);
	Route::get('/group/contacts/{id}', 'User\GroupsController@postDeleteContacts')->name('user.group.contacts.delete')->middleware(['permission:user.group.view']);

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

	Route::get('/message/instance-update/{id}', 'User\ChatBot\BotInstanceController@getInstanceUpdate')->name('user.chat.bot.instance.update')->middleware(['permission:user.chat.bot.instance.update']);
	Route::post('/message/instance-update/{id}', 'User\ChatBot\BotInstanceController@postInstanceUpdate')->name('user.chat.bot.instance.update')->middleware(['permission:user.chat.bot.instance.update']);

	//API MANAGEMENT
	Route::get('/api/view', 'User\Api\ApiController@getApiView')->name('api.key.view')->middleware(['permission:api.key.view']);
	Route::get('/api/create', 'User\Api\ApiController@getApiCreate')->name('api.key.create')->middleware(['permission:api.key.create']);
	Route::post('/api/create', 'User\Api\ApiController@postApiCreate')->name('api.key.create')->middleware(['permission:api.key.create']);

	Route::post('/api/block-api', 'User\Api\ApiController@postBlockApi')->name('api.block.api');


});
Route::group(['middleware' => 'auth'], function () {
//PROFILE MANAGEMENT
Route::get('/my/profile', 'User\UserController@getProfileDetail')->name('global.my.profile')->middleware(['permission:global.my.profile']);
Route::post('/my/profile', 'User\UserController@postProfileDetail')->name('global.my.profile')->middleware(['permission:global.my.profile']);
//default dashboard
Route::get('dashboard', 'Auth\AuthController@defaultlanding')->name('default.dashboard')->middleware(['permission:default.dashboard']);

//API DOCUMENTAION
Route::get('/api/documentation', 'User\UserController@getDocumentation')->name('api.documentation')->middleware(['permission:api.documentation']);

//globalAjaxcall
Route::post('/ajax/request-approve', 'Web\AjaxController@postRequestApprove')->name('ajax.request.approve')->middleware(['permission:ajax.request.approve']);
Route::post('/ajax/request-reject', 'Auth\AjaxController@postRequestReject')->name('ajax.request.reject')->middleware(['permission:ajax.request.reject']);
//user campaign cancel
Route::post('/ajax/cancel-campaign', 'Web\AjaxController@getCancelCampaign')->name('ajax.cancel.campaign');
Route::post('/ajax/block-user', 'Web\AjaxController@getBlockUser')->name('ajax.block.user');
//DELETE CHAT INSTANCE
Route::post('/ajax-chat/instance-delete', 'Web\AjaxController@getInstanceDelete')->name('user.chat.bot.instance.delete');
Route::post('/ajax-chat/message-response-delete', 'Web\AjaxController@getMessageResponseDelete')->name('user.chat.bot.message.response.delete');
Route::post('/ajax-chat/menu-response-delete', 'Web\AjaxController@getMenuResponseDelete')->name('user.chat.bot.menu.response.delete');
Route::post('/ajax-chat/block-api', 'Web\AjaxController@postBlockApi')->name('api.block.api');
//GROUP AJAX CALL
Route::post('/ajax/group-contacts-getter', 'Web\AjaxController@postRequestGroupContacts')->name('ajax.group.contacts');

// Message Request - AJax section starts

Route::post('/ajax/message-request/next-app-name', 'Web\AjaxController@getNextAppName')->name('ajax.message.request.appname');
Route::post('/ajax/message-request/bodies', 'Web\AjaxController@postBodies')->name('ajax.message.request.bodies');

// ends

Route::post('/ajax/current-recharge-status-change', 'Web\AjaxController@postCurrentStatus')->name('ajax.current.status.change');
});
//Errors
Route::get('/permission/denid/403', 'Web\HomeController@accessDenied')->name('accessDenied');