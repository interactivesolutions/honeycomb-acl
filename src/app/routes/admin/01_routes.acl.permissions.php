<?php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/permissions', ['as' => 'admin.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminView']);

    Route::group(['prefix' => 'api/users/permissions'], function ()
    {
        Route::get('/', ['as' => 'admin.api.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listPage']);
        Route::get('list/{timestamp}', ['as' => 'admin.api.acl.permissions.list.update', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listUpdate']);
        Route::get('/list', ['as' => 'admin.api.acl.permissions.list', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@list']);
        Route::get('/search', ['as' => 'admin.api.acl.permissions.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listSearch']);
    });
});
