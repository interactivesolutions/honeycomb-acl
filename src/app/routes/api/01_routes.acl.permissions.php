<?php

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function ()
{
    Route::group(['prefix' => 'v1/users/permissions'], function ()
    {
        Route::get('/', ['as' => 'api.v1.acl.permissions', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@apiIndexPaginate']);

        Route::group(['prefix' => 'list'], function (){
            Route::get('/', ['as' => 'api.v1.acl.permissions.list', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@apiIndex']);
            Route::get('{timestamp}', ['as' => 'api.v1.acl.permissions.list.update', 'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@apiIndexSync']);
        });
    });
});
