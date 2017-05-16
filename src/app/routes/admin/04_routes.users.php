<?php

Route::group (['prefix' => env('HC_ADMIN_URL'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get ('users', ['as' => 'admin.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@adminIndex']);

    Route::group (['prefix' => 'api/users'], function () {
        Route::get ('/', ['as' => 'admin.api.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiIndexPaginate']);
        Route::post ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@apiStore']);
        Route::delete ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@apiDestroy']);

        Route::post ('restore', ['as' => 'admin.api.users.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiRestore']);
        Route::post ('merge', ['as' => 'admin.api.users.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create', 'acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@apiMerge']);
        Route::delete ('force', ['as' => 'admin.api.users.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function (){

            Route::get ('/', ['as' => 'admin.api.users.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@apiShow']);
            Route::put ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiUpdate']);
            Route::delete ('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.users.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@apiUpdateStrict']);
            Route::post ('duplicate', ['as' => 'admin.api.users.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@apiDuplicate']);
            Route::delete ('force', ['as' => 'admin.api.users.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@apiForceDelete']);
        });
    });
});
