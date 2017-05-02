<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::get('users/roles', ['as' => 'api.v1.acl.roles', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@adminView']);

    Route::group(['prefix' => 'v1/users/roles'], function ()
    {
        Route::get('/', ['as' => 'api.v1.api.acl.roles', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listPage']);
        Route::get('list', ['as' => 'api.v1.api.acl.roles.list', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@list']);
        Route::get('list/{timestamp}', ['as' => 'api.v1.api.acl.roles.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listUpdate']);
        Route::get('search', ['as' => 'api.v1.api.acl.roles.search', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listSearch']);
        Route::get('{id}', ['as' => 'api.v1.api.acl.roles.single', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@single']);

        Route::post('{id}/duplicate', ['as' => 'api.v1.api.acl.roles.duplicate', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@duplicate']);
        Route::post('restore', ['as' => 'api.v1.api.acl.roles.restore', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@restore']);
        Route::post('merge', ['as' => 'api.v1.api.acl.roles.merge', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@merge']);
        Route::post('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@create']);

        Route::put('{id}', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@update']);

        Route::delete('{id}', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('/', ['middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('{id}/force', ['as' => 'api.v1.api.acl.roles.force', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
        Route::delete('force', ['as' => 'api.v1.api.acl.roles.force.multi', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
    });
});
