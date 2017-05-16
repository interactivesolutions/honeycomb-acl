<?php

Route::group (['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group (['prefix' => 'v1/users'], function ()
    {
        Route::get ('/', ['as' => 'api.v1.users', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiIndexPaginate']);
        Route::post ('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@apiStore']);
        Route::delete ('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@apiDestroy']);


        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.users.list', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiIndex']);
            Route::get('{timestamp}', ['as' => 'api.v1.users.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiIndexSync']);
        });

        Route::post ('restore', ['as' => 'api.v1.users.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiRestore']);
        Route::post ('merge', ['as' => 'api.v1.users.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_create', 'acl-apps:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiMerge']);
        Route::delete ('force', ['as' => 'api.v1.users.force.multi', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get ('/', ['as' => 'api.v1.users.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiShow']);
            Route::put ('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiUpdate']);
            Route::delete ('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@apiDestroy']);
            Route::post ('duplicate', ['as' => 'api.v1.users.duplicate', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiDuplicate']);
            Route::delete ('force', ['as' => 'api.v1.users.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@apiForceDelete']);
        });
    });
});
