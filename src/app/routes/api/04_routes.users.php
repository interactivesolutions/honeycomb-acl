<?php

Route::group (['prefix' => 'api', 'middleware' => ['web', 'auth-apps']], function () {
    Route::get ('users', ['as' => 'api.v1.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@adminView']);

    Route::group (['prefix' => 'v1/users'], function () {
        Route::get ('/', ['as' => 'api.v1.api.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listPage']);
        Route::get ('list', ['as' => 'api.v1.api.users.list', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@list']);
        Route::get ('list/{timestamp}', ['as' => 'api.v1.api.users.list.update', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listUpdate']);
        Route::get ('search', ['as' => 'api.v1.api.users.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listSearch']);
        Route::get ('{id}', ['as' => 'api.v1.api.users.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@getSingleRecord']);

        Route::post ('{id}/duplicate', ['as' => 'api.v1.api.users.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@duplicate']);
        Route::post ('restore', ['as' => 'api.v1.api.users.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@restore']);
        Route::post ('merge', ['as' => 'api.v1.api.users.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@merge']);
        Route::post ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@create']);

        Route::put ('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@update']);

        Route::delete ('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('{id}/force', ['as' => 'api.v1.api.users.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
        Route::delete ('force', ['as' => 'api.v1.api.users.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
    });
});

Route::get ('api', ['as' => 'api.v1.index', 'middleware' => ['web', 'auth'], 'uses' => 'HCAuthController@showLogin']);
