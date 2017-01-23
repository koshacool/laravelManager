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

//Route::get('/', ['uses' => 'UsersController@authorisation']);
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('authorisation', ['uses' => 'UsersController@authorisation']);
Route::get('users/registration', ['uses' => 'UsersController@registration']);
Route::post('users/registration', ['uses' => 'UsersController@create']);

//Laravel auth
Auth::routes();

Route::get('/showlist', 'ContactController@showlist');
Route::post('/showlist', 'ContactController@showlist');

Route::get('/record', 'ContactController@record');
//Route::post('/record', 'ContactController@record');
Route::get('/record/{id}', 'ContactController@record');
Route::post('/record/{id}', 'ContactController@record');

Route::get('/view/{id}', 'ContactController@view');

Route::get('/remove', 'ContactController@remove');
Route::get('/remove/{id}', 'ContactController@remove');
Route::post('/remove/{id}', 'ContactController@remove');

Route::get('/emails', 'ContactController@emails');
Route::post('/emails', 'ContactController@emails');

Route::get('/select', 'ContactController@select');
Route::post('/select', 'ContactController@select');

