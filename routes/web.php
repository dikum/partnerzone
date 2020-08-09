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

Route::get('/create-message', 'Message\MessageController@create');
Route::post('/send-message', 'Message\MessageController@send');


Route::get('/message-templates', 'Message\MessageController@show_templates');
Route::get('/message_template-create', 'Message\MessageController@create_template');
Route::post('/save-message-template', 'Message\MessageController@save_template');
Route::get('/delete-message-template/{message}', 'Message\MessageController@delete');
Route::get('/get-template/{template}', 'Message\MessageController@get_template');

Route::get('/countries', 'Country\CountryController@index');

Route::get('/states', 'State\StateController@index');

Route::get('/currencies', 'Currency\CurrencyController@index');



Route::get('/test', 'Authentication\LoginController@test');


