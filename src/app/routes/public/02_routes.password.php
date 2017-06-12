<?php

Route::group(['prefix' => 'password', 'middleware' => ['web']], function () {
    Route::get('remind', ['as' => 'users.password.remind', 'middleware' => 'guest', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
    Route::post('remind', ['as' => 'users.password.remind.post', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);

    Route::get('reset/{token}', ['as' => 'users.password.reset', 'middleware' => 'guest', 'uses' => 'ResetPasswordController@showResetForm']);
    Route::post('reset', ['as' => 'users.password.reset.post', 'uses' => 'ResetPasswordController@reset']);
});
