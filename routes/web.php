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

Route::get('/', '\App\Http\Controllers\HomeController@index');


Route::group(['namespace'=>'App\Http\Controllers\Auth'], function(){

    Route::get('/auth/sso/eve/redirect', [
        'as'    => 'sso.eve.redirect',
        'uses'  => 'EveLoginController@redirect'
    ]);

    Route::get('/auth/sso/eve/callback', [
        'as'    => 'sso.eve.callback',
        'uses'  => 'EveLoginController@callback'
    ]);

});


