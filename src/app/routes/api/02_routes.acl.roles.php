<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/users/roles'], function ()
    {
        Route::get('/', ['as' => 'api.v1.acl.roles', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@apiDestroy']);

        Route::group(['prefix' => 'list'], function () {
            Route::get('/', ['as' => 'api.v1.acl.roles.list', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiIndex']);
            Route::get('{timestamp}', ['as' => 'api.v1.acl.roles.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.acl.roles.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.acl.roles.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_create', 'acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.acl.roles.force.multi', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.acl.roles.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@apiDestroy']);

            Route::post('duplicate', ['as' => 'api.v1.acl.roles.duplicate', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.acl.roles.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@apiForceDelete']);
        });

    });
});
