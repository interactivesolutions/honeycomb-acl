<?php

// login routes
Route::group (['prefix' => 'auth', 'middleware' => ['web']], function () {
    Route::get ('login', ['as' => 'auth.login', 'middleware' => 'guest', 'uses' => 'HCAuthController@showLogin']);
    Route::post ('login', ['as' => 'auth.login', 'uses' => 'HCAuthController@login']);

    Route::get('register', ['as' => 'auth.register', 'middleware' => 'guest', 'uses' => 'HCAuthController@showRegister']);
    Route::post('register', ['as' => 'auth.register', 'uses' => 'HCAuthController@register']);

    Route::get ('logout', ['as' => 'auth.logout', 'middleware' => 'auth', 'uses' => 'HCAuthController@logout']);
});