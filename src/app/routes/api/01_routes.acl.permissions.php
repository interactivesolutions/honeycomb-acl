<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::get('users/permissions', ['as' => 'api.v1.acl.permissions', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminView']);

    Route::group(['prefix' => 'v1/users/permissions'], function ()
    {
        Route::get('/', ['as' => 'api.v1.acl.permissions', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listPage']);
        Route::get('list/{timestamp}', ['as' => 'api.v1.acl.permissions.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listUpdate']);
        Route::get('/list', ['as' => 'api.v1.acl.permissions.list', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@list']);
        Route::get('/search', ['as' => 'api.v1.acl.permissions.search', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listSearch']);
    });
});
