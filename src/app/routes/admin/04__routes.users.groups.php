<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/groups', ['as' => 'admin.routes.users.groups.index', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@adminIndex']);

    Route::group(['prefix' => 'api/users/groups'], function ()
    {
        Route::get('/', ['as' => 'admin.api.routes.users.groups', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_create'], 'uses' => 'users\\HCGroupsController@apiStore']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiDestroy']);

        Route::get('list', ['as' => 'admin.api.routes.users.groups.list', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiIndex']);
        Route::post('restore', ['as' => 'admin.api.routes.users.groups.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.users.groups.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_create', 'acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiMerge']);
        Route::delete('force', ['as' => 'admin.api.routes.users.groups.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'], 'uses' => 'users\\HCGroupsController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'admin.api.routes.users.groups.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiShow']);
            Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiDestroy']);

            Route::put('strict', ['as' => 'admin.api.routes.users.groups.update.strict', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'admin.api.routes.users.groups.duplicate.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list', 'acl:interactivesolutions_honeycomb_acl_routes_users_groups_create'], 'uses' => 'users\\HCGroupsController@apiDuplicate']);
            Route::delete('force', ['as' => 'admin.api.routes.users.groups.force.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'], 'uses' => 'users\\HCGroupsController@apiForceDelete']);
        });
    });
});
