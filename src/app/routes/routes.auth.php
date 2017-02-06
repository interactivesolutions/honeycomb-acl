<?php

// login routes
Route::group (['prefix' => 'auth', 'middleware' => ['web']], function () {
    Route::get ('login', ['as' => 'auth.login', 'middleware' => 'guest', 'uses' => 'HCAuthController@showLogin']);
    Route::post ('login', ['as' => 'auth.login', 'uses' => 'HCAuthController@login']);

    Route::get ('logout', ['as' => 'auth.logout', 'middleware' => 'auth', 'uses' => 'HCAuthController@logout']);
});