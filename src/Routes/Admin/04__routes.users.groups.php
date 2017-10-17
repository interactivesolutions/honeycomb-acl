<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

Route::group(['prefix' => config('hc.admin_url'), 'middleware' => ['web', 'auth']], function() {
    Route::get('users/groups', [
        'as' => 'admin.routes.users.groups.index',
        'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
        'uses' => 'Users\\HCGroupsController@adminIndex',
    ]);

    Route::group(['prefix' => 'api/users/groups'], function() {
        Route::get('/', [
            'as' => 'admin.api.routes.users.groups',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
            'uses' => 'Users\\HCGroupsController@apiIndexPaginate',
        ]);
        Route::post('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_create'],
            'uses' => 'Users\\HCGroupsController@apiStore',
        ]);
        Route::delete('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete'],
            'uses' => 'Users\\HCGroupsController@apiDestroy',
        ]);

        Route::get('list', [
            'as' => 'admin.api.routes.users.groups.list',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
            'uses' => 'Users\\HCGroupsController@apiIndex',
        ]);
        Route::post('restore', [
            'as' => 'admin.api.routes.users.groups.restore',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
            'uses' => 'Users\\HCGroupsController@apiRestore',
        ]);
        Route::post('merge', [
            'as' => 'api.v1.routes.users.groups.merge',
            'middleware' => [
                'acl:interactivesolutions_honeycomb_acl_routes_users_groups_create',
                'acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete',
            ],
            'uses' => 'Users\\HCGroupsController@apiMerge',
        ]);
        Route::delete('force', [
            'as' => 'admin.api.routes.users.groups.force',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'],
            'uses' => 'Users\\HCGroupsController@apiForceDelete',
        ]);

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/', [
                'as' => 'admin.api.routes.users.groups.single',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
                'uses' => 'Users\\HCGroupsController@apiShow',
            ]);
            Route::put('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
                'uses' => 'Users\\HCGroupsController@apiUpdate',
            ]);
            Route::delete('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_delete'],
                'uses' => 'Users\\HCGroupsController@apiDestroy',
            ]);

            Route::put('strict', [
                'as' => 'admin.api.routes.users.groups.update.strict',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
                'uses' => 'Users\\HCGroupsController@apiUpdateStrict',
            ]);
            Route::post('duplicate', [
                'as' => 'admin.api.routes.users.groups.duplicate.single',
                'middleware' => [
                    'acl:interactivesolutions_honeycomb_acl_routes_users_groups_list',
                    'acl:interactivesolutions_honeycomb_acl_routes_users_groups_create',
                ],
                'uses' => 'Users\\HCGroupsController@apiDuplicate',
            ]);
            Route::delete('force', [
                'as' => 'admin.api.routes.users.groups.force.single',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'],
                'uses' => 'Users\\HCGroupsController@apiForceDelete',
            ]);
        });
    });
});
