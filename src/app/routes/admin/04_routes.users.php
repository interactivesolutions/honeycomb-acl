<?php

Route::group (['prefix' => env('HC_ADMIN_URL'), 'middleware' => ['web', 'auth']], function () {
    Route::get ('users', ['as' => 'admin.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@adminView']);

    Route::group (['prefix' => 'api/users'], function () {
        Route::get ('/', ['as' => 'admin.api.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listPage']);
        Route::get ('list', ['as' => 'admin.api.users.list', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@list']);
        Route::get ('list/{timestamp}', ['as' => 'admin.api.users.list.update', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listUpdate']);
        Route::get ('search', ['as' => 'admin.api.users.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listSearch']);
        Route::get ('{id}', ['as' => 'admin.api.users.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@getSingleRecord']);

        Route::post ('{id}/duplicate', ['as' => 'admin.api.users.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@duplicate']);
        Route::post ('restore', ['as' => 'admin.api.users.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@restore']);
        Route::post ('merge', ['as' => 'admin.api.users.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@merge']);
        Route::post ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@create']);

        Route::put ('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@update']);

        Route::delete ('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('{id}/force', ['as' => 'admin.api.users.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
        Route::delete ('force', ['as' => 'admin.api.users.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
    });
});

Route::get (env('HC_ADMIN_URL'), ['as' => 'admin.index', 'middleware' => ['web', 'auth'], 'uses' => 'HCAuthController@showLogin']);
