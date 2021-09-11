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

Route::get('/', '\App\Http\Controllers\HomeController@index')->name('login');


Route::group(['namespace'=>'App\Http\Controllers\Auth'], function(){

    Route::get('/auth/sso/eve/redirect', [
        'as'    => 'sso.eve.redirect',
        'uses'  => 'EveLoginController@redirect'
    ]);

    Route::get('/auth/callback', [
        'as'    => 'sso.eve.callback',
        'uses'  => 'EveLoginController@callback'
    ]);

});

Route::group([
    'middleware'=> ['web', 'auth'],
    'namespace' => 'App\Http\Controllers',
], function(){

    Route::get('/home', 'HomeController@home')->name('main');

    Route::get('/logout', 'HomeController@logout')->name('logout');

    route::get('/debugging', 'HomeController@toggleDebug')->name('debugging');

    Route::post('/character/{character}/toggleTracking',
        'CharacterController@toggleTracking')
        ->name('character.tracking.toggle');

    Route::post('/character/{character}/getLocation', 'CharacterController@getLocation')
        ->name('character.location.fetch');

    Route::post('/character/{character}/getOnline', 'CharacterController@getOnline')
        ->name('character.location.online');

     Route::post('/character/{character}/queueLocation', 'CharacterController@queueLocation')
        ->name('character.location.queue');
});



