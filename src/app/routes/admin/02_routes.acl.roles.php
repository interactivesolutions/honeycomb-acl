<?php

Route::group(['prefix' => env('HC_ADMIN_URL'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/roles', ['as' => 'admin.acl.roles.index', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@adminIndex']);

    Route::group(['prefix' => 'api/users/roles'], function () {
        Route::get('/', ['as' => 'admin.api.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@apiDestroy']);

        Route::post('restore', ['as' => 'admin.api.acl.roles.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiRestore']);
        Route::post('merge', ['as' => 'admin.api.acl.roles.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create', 'acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.acl.roles.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function () {
            Route::get('/', ['as' => 'admin.api.acl.roles.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.acl.roles.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.acl.roles.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.acl.roles.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@apiForceDelete']);
        });
    });
});
