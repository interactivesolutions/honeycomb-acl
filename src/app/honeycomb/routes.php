<?php
//./packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.acl.permissions.php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('acl/permissions', ['as' => 'admin.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminView']);

    Route::group(['prefix' => 'api'], function ()
    {
        Route::get('acl/permissions', ['as' => 'admin.api.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listData']);
        Route::get('acl/permissions/search', ['as' => 'admin.api.acl.permissions.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@search']);
        Route::get('acl/permissions/{id}', ['as' => 'admin.api.acl.permissions.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@single']);
        Route::post('acl/permissions/{id}/duplicate', ['as' => 'admin.api.acl.permissions.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@duplicate']);
        Route::post('acl/permissions/restore', ['as' => 'admin.api.acl.permissions.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@restore']);
        Route::post('acl/permissions/merge', ['as' => 'admin.api.acl.permissions.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@merge']);
        Route::post('acl/permissions', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_create'], 'uses' => 'acl\\PermissionsController@create']);
        Route::put('acl/permissions/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_update'], 'uses' => 'acl\\PermissionsController@update']);
        Route::delete('acl/permissions/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_delete'], 'uses' => 'acl\\PermissionsController@delete']);
        Route::delete('acl/permissions', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_delete'], 'uses' => 'acl\\PermissionsController@delete']);
        Route::delete('acl/permissions/{id}/force', ['as' => 'admin.api.acl.permissions.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_force_delete'], 'uses' => 'acl\\PermissionsController@forceDelete']);
        Route::delete('acl/permissions/force', ['as' => 'admin.api.acl.permissions.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_force_delete'], 'uses' => 'acl\\PermissionsController@forceDelete']);
    });
});


//./packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.acl.roles.php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('acl/roles', ['as' => 'admin.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@adminView']);

    Route::group(['prefix' => 'api'], function ()
    {
        Route::get('acl/roles', ['as' => 'admin.api.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listData']);
        Route::get('acl/roles/search', ['as' => 'admin.api.acl.roles.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@search']);
        Route::get('acl/roles/{id}', ['as' => 'admin.api.acl.roles.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@single']);
        Route::post('acl/roles/{id}/duplicate', ['as' => 'admin.api.acl.roles.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@duplicate']);
        Route::post('acl/roles/restore', ['as' => 'admin.api.acl.roles.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@restore']);
        Route::post('acl/roles/merge', ['as' => 'admin.api.acl.roles.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@merge']);
        Route::post('acl/roles', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@create']);
        Route::put('acl/roles/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@update']);
        Route::delete('acl/roles/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('acl/roles', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('acl/roles/{id}/force', ['as' => 'admin.api.acl.roles.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
        Route::delete('acl/roles/force', ['as' => 'admin.api.acl.roles.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
    });
});

