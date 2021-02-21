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


Route::get('/', 'DashboardController@index');

Route::get('/user-login', 'Authentication\LoginController@index');
Route::post('/login-action', 'Authentication\LoginController@login_action');
Route::get('/logout', 'Authentication\LoginController@logout');


Route::get('/partners', 'Partner\PartnerController@index');
Route::get('/show-partner', 'Partner\PartnerController@show');
Route::post('/search-partner', 'Partner\PartnerController@search');
Route::post('/update-partner', 'Partner\PartnerController@update');
Route::get('/delete-partner/{partner}', 'Partner\PartnerController@delete');
Route::get('/partner-payments/{partner}', 'Partner\PartnerController@payments');
Route::get('/partner-create/', 'Partner\PartnerController@create');
Route::get('/delete-payment/{payment}', 'Payment\PaymentController@delete');
Route::post('/register-partner/', 'Partner\PartnerController@store');


Route::get('/payments', 'Payment\PaymentController@index');
Route::post('/search-payments', 'Payment\PaymentController@search');
Route::post('/enter-sr-payment', 'Payment\PaymentController@save_strong_room_payment');

Route::get('/create-message', 'Message\MessageController@create');
Route::post('/send-bulk-message', 'Message\MessageController@send_bulk');
//Route::get('/cancel_message_sending', 'Message\MessageController@cancel_job');


Route::get('/show-statement', 'BankStatement\BankStatementController@show');
Route::post('/import-statement', 'BankStatement\BankStatementController@import_bank_statement');
Route::post('/search-statement', 'BankStatement\BankStatementController@search');


Route::get('/message-templates', 'Message\MessageController@show_templates');
Route::get('/message_template-create', 'Message\MessageController@create_template');
Route::post('/save-message-template', 'Message\MessageController@save_template');
Route::get('/delete-message-template/{message}', 'Message\MessageController@delete');
Route::get('/get-template/{template}', 'Message\MessageController@get_template');
Route::get('/message-log', 'Message\MessageController@message_log');
Route::post('/search-message-log', 'Message\MessageController@search');


Route::get('/countries', 'Country\CountryController@index');

Route::get('/states', 'State\StateController@index');

Route::get('/currencies', 'Currency\CurrencyController@index');

Route::get('/get-new-notifications', 'Notification\NotificationController@get_new_notifications');

Route::get('/test', 'Authentication\LoginController@test');


