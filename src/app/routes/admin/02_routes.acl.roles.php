<?php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/roles', ['as' => 'admin.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@adminView']);

    Route::group(['prefix' => 'api/users/roles'], function ()
    {
        Route::get('/', ['as' => 'admin.api.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listPage']);
        Route::get('list', ['as' => 'admin.api.acl.roles.list', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@list']);
        Route::get('list/{timestamp}', ['as' => 'admin.api.acl.roles.list.update', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listUpdate']);
        Route::get('search', ['as' => 'admin.api.acl.roles.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listSearch']);
        Route::get('{id}', ['as' => 'admin.api.acl.roles.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@single']);

        Route::post('{id}/duplicate', ['as' => 'admin.api.acl.roles.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@duplicate']);
        Route::post('restore', ['as' => 'admin.api.acl.roles.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@restore']);
        Route::post('merge', ['as' => 'admin.api.acl.roles.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@merge']);
        Route::post('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@create']);

        Route::put('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@update']);

        Route::delete('{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('{id}/force', ['as' => 'admin.api.acl.roles.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
        Route::delete('force', ['as' => 'admin.api.acl.roles.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
    });
});
