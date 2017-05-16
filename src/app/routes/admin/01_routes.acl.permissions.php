<?php

Route::group(['prefix' => env('HC_ADMIN_URL'), 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/permissions', ['as' => 'admin.acl.permissions.index', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminIndex']);

    Route::group(['prefix' => 'api/users/permissions'], function ()
    {
        Route::get('/', ['as' => 'admin.api.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@apiIndexPaginate']);
    });
});

