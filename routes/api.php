<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    // --------------- Register and Login ----------------//
    Route::post('register', 'AuthenticationController@register')->name('register');
    Route::post('login', 'AuthenticationController@login')->name('login');
    
    // ------------------ Get Data ----------------------//
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('get-user', 'AuthenticationController@userInfo')->name('get-user');
        Route::post('logout', 'AuthenticationController@logOut')->name('logout');
        Route::post('discussions', 'DiscussionController@store')->name('store');
        Route::get('discussions', 'DiscussionController@index')->name('index');
        Route::get('discussions/{id}/messages', 'MessageController@getMessage')->name('get-message');
        Route::post('discussions/{id}/messages', 'MessageController@storeMessage')->name('store-message');
    });
});