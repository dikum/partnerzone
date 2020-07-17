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


Route::get('/countries', 'Country\CountryController@index');

Route::get('/states', 'State\StateController@index');

Route::get('/currencies', 'Currency\CurrencyController@index');



Route::get('/test', 'Authentication\LoginController@test');


