<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/users/groups'], function ()
    {
        Route::get('/', ['as' => 'api.v1.routes.users.groups', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiIndexPaginate']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create'], 'uses' => 'users\\HCGroupsController@apiStore']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiDestroy']);

        Route::group(['prefix' => 'list'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.users.groups.list', 'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiList']);
            Route::get('{timestamp}', ['as' => 'api.v1.routes.users.groups.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiIndexSync']);
        });

        Route::post('restore', ['as' => 'api.v1.routes.users.groups.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiRestore']);
        Route::post('merge', ['as' => 'api.v1.routes.users.groups.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create', 'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiMerge']);
        Route::delete('force', ['as' => 'api.v1.routes.users.groups.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'], 'uses' => 'users\\HCGroupsController@apiForceDelete']);

        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', ['as' => 'api.v1.routes.users.groups.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'], 'uses' => 'users\\HCGroupsController@apiShow']);
            Route::put('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiUpdate']);
            Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete'], 'uses' => 'users\\HCGroupsController@apiDestroy']);

            Route::put('strict', ['as' => 'api.v1.routes.users.groups.update.strict', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'], 'uses' => 'users\\HCGroupsController@apiUpdateStrict']);
            Route::post('duplicate', ['as' => 'api.v1.routes.users.groups.duplicate.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list', 'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create'], 'uses' => 'users\\HCGroupsController@apiDuplicate']);
            Route::delete('force', ['as' => 'api.v1.routes.users.groups.force.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'], 'uses' => 'users\\HCGroupsController@apiForceDelete']);
        });
    });
});