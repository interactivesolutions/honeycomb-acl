<?php

//packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.users.php

Route::group (['prefix' => 'admin', 'middleware' => ['web', 'auth']], function () {
    Route::get ('users', ['as' => 'admin.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@adminView']);

    Route::group (['prefix' => 'api'], function () {
        Route::get ('users', ['as' => 'admin.api.users', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@listData']);
        Route::get ('users/search', ['as' => 'admin.api.users.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@search']);
        Route::get ('users/{id}', ['as' => 'admin.api.users.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'], 'uses' => 'HCUsersController@getSingleRecord']);
        Route::post ('users/{id}/duplicate', ['as' => 'admin.api.users.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@duplicate']);
        Route::post ('users/restore', ['as' => 'admin.api.users.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@restore']);
        Route::post ('users/merge', ['as' => 'admin.api.users.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@merge']);
        Route::post ('users', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'], 'uses' => 'HCUsersController@create']);
        Route::put ('users/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'], 'uses' => 'HCUsersController@update']);
        Route::delete ('users/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('users', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'], 'uses' => 'HCUsersController@delete']);
        Route::delete ('users/{id}/force', ['as' => 'admin.api.users.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
        Route::delete ('users/force', ['as' => 'admin.api.users.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'], 'uses' => 'HCUsersController@forceDelete']);
    });
});

Route::get ('admin', ['as' => 'admin.index', 'middleware' => ['web', 'auth'], 'uses' => 'HCAuthController@showLogin']);


//packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.auth.php

// login routes
Route::group (['prefix' => 'auth', 'middleware' => ['web']], function () {
    Route::get ('login', ['as' => 'auth.login', 'middleware' => 'guest', 'uses' => 'HCAuthController@showLogin']);
    Route::post ('login', ['as' => 'auth.login', 'uses' => 'HCAuthController@login']);

    Route::get('register', ['as' => 'auth.register', 'middleware' => 'guest', 'uses' => 'HCAuthController@showRegister']);
    Route::post('register', ['as' => 'auth.register', 'uses' => 'HCAuthController@register']);

    Route::get ('logout', ['as' => 'auth.logout', 'middleware' => 'auth', 'uses' => 'HCAuthController@logout']);
});

Route::get ('admin', ['as' => 'auth.logout', 'middleware' => ['web', 'auth'], 'uses' => 'HCAuthController@showLogin']);


//packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.acl.roles.php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/roles', ['as' => 'admin.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@adminView']);

    Route::group(['prefix' => 'api'], function ()
    {
        Route::get('users/roles', ['as' => 'admin.api.acl.roles', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@listData']);
        Route::get('users/roles/search', ['as' => 'admin.api.acl.roles.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@search']);
        Route::get('users/roles/{id}', ['as' => 'admin.api.acl.roles.single', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'], 'uses' => 'acl\\RolesController@single']);
        Route::post('users/roles/{id}/duplicate', ['as' => 'admin.api.acl.roles.duplicate', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@duplicate']);
        Route::post('users/roles/restore', ['as' => 'admin.api.acl.roles.restore', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@restore']);
        Route::post('users/roles/merge', ['as' => 'admin.api.acl.roles.merge', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@merge']);
        Route::post('users/roles', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'], 'uses' => 'acl\\RolesController@create']);
        Route::put('users/roles/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'], 'uses' => 'acl\\RolesController@update']);
        Route::delete('users/roles/{id}', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('users/roles', ['middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'], 'uses' => 'acl\\RolesController@delete']);
        Route::delete('users/roles/{id}/force', ['as' => 'admin.api.acl.roles.force', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
        Route::delete('users/roles/force', ['as' => 'admin.api.acl.roles.force.multi', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'], 'uses' => 'acl\\RolesController@forceDelete']);
    });
});


//packages/interactivesolutions/honeycomb-acl/src/app/routes/routes.acl.permissions.php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('users/permissions', ['as' => 'admin.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@adminView']);

    Route::group(['prefix' => 'api'], function ()
    {
        Route::get('users/permissions', ['as' => 'admin.api.acl.permissions', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@listData']);
        Route::get('users/permissions/search', ['as' => 'admin.api.acl.permissions.search', 'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_permissions_list'], 'uses' => 'acl\\PermissionsController@search']);
    });
});

