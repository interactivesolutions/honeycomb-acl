<?php

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function () {
    Route::get('users/access', ['as' => 'admin.acl.access.index', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_access_list'], 'uses' => 'acl\\HCAccessController@adminIndex']);

    Route::group(['prefix' => 'api/users/access'], function () {
        Route::put('/', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_access_update'], 'as' => 'admin.api.acl.access.update', 'uses' => 'acl\\HCAccessController@updateAccess']);
    });
});
