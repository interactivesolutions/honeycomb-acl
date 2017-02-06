<?php

Route::group (['prefix' => 'admin', 'middleware' => ['web', 'auth']], function () {
    Route::get ('users', ['as' => 'admin.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => '\\HCUsersController@adminView']);

    Route::group (['prefix' => 'api'], function () {
        Route::get ('users', ['as' => 'admin.api.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => '\\HCUsersController@listData']);
        Route::get ('users/search', ['as' => 'admin.api.users.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => '\\HCUsersController@search']);
        Route::get ('users/{id}', ['as' => 'admin.api.users.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => '\\HCUsersController@getSingleRecord']);
        Route::post ('users/{id}/duplicate', ['as' => 'admin.api.users.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => '\\HCUsersController@duplicate']);
        Route::post ('users/restore', ['as' => 'admin.api.users.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => '\\HCUsersController@restore']);
        Route::post ('users/merge', ['as' => 'admin.api.users.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => '\\HCUsersController@merge']);
        Route::post ('users', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => '\\HCUsersController@create']);
        Route::put ('users/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => '\\HCUsersController@update']);
        Route::delete ('users/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => '\\HCUsersController@delete']);
        Route::delete ('users', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => '\\HCUsersController@delete']);
        Route::delete ('users/{id}/force', ['as' => 'admin.api.users.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => '\\HCUsersController@forceDelete']);
        Route::delete ('users/force', ['as' => 'admin.api.users.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => '\\HCUsersController@forceDelete']);
    });
});
