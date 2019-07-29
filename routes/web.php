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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('invoice', 'InvoiceController');

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function() {
    Route::get('redirect', 'ApiController@redirect')->name('api.redirect');
    Route::post('disconnect', 'ApiController@clearApiToken')->name('api.disconnect');
    Route::get('validate', 'ApiController@callback');
});
