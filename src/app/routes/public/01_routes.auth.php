<?php

// login routes
Route::group (['prefix' => 'auth', 'middleware' => ['web']], function ()
{
    Route::get ('login', ['as' => 'auth.index', 'middleware' => 'guest', 'uses' => 'HCAuthController@showLogin']);
    Route::post ('login', ['as' => 'auth.login', 'uses' => 'HCAuthController@login']);

    Route::get('register', ['as' => 'auth.register', 'middleware' => 'guest', 'uses' => 'HCAuthController@showRegister']);
    Route::post('register', ['uses' => 'HCAuthController@register']);

    Route::get('activation/{token}', ['as' => 'auth.activation', 'middleware' => 'guest', 'uses' => 'HCAuthController@showActivation']);
    Route::post('activation', ['as' => 'auth.activation.post', 'uses' => 'HCAuthController@activate']);

    Route::get ('logout', ['as' => 'auth.logout', 'middleware' => 'auth', 'uses' => 'HCAuthController@logout']);
});