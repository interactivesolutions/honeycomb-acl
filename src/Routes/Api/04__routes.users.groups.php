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
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

Route::group(['prefix' => 'api', 'middleware' => ['auth-apps']], function() {
    Route::group(['prefix' => 'v1/users/groups'], function() {
        Route::get('/', [
            'as' => 'api.v1.routes.users.groups',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
            'uses' => 'Users\\HCGroupsController@apiIndexPaginate',
        ]);
        Route::post('/', [
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create'],
            'uses' => 'Users\\HCGroupsController@apiStore',
        ]);
        Route::delete('/', [
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete'],
            'uses' => 'Users\\HCGroupsController@apiDestroy',
        ]);

        Route::group(['prefix' => 'list'], function() {
            Route::get('/', [
                'as' => 'api.v1.routes.users.groups.list',
                'middleware' => ['acl-apps:api_v1_interactivesolutions_honeycomb_acl_routes_users_groups_list'],
                'uses' => 'Users\\HCGroupsController@apiList',
            ]);
            Route::get('{timestamp}', [
                'as' => 'api.v1.routes.users.groups.list.update',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
                'uses' => 'Users\\HCGroupsController@apiIndexSync',
            ]);
        });

        Route::post('restore', [
            'as' => 'api.v1.routes.users.groups.restore',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
            'uses' => 'Users\\HCGroupsController@apiRestore',
        ]);
        Route::post('merge', [
            'as' => 'api.v1.routes.users.groups.merge',
            'middleware' => [
                'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create',
                'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete',
            ],
            'uses' => 'Users\\HCGroupsController@apiMerge',
        ]);
        Route::delete('force', [
            'as' => 'api.v1.routes.users.groups.force',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'],
            'uses' => 'Users\\HCGroupsController@apiForceDelete',
        ]);

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/', [
                'as' => 'api.v1.routes.users.groups.single',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list'],
                'uses' => 'Users\\HCGroupsController@apiShow',
            ]);
            Route::put('/', [
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
                'uses' => 'Users\\HCGroupsController@apiUpdate',
            ]);
            Route::delete('/', [
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_delete'],
                'uses' => 'Users\\HCGroupsController@apiDestroy',
            ]);

            Route::put('strict', [
                'as' => 'api.v1.routes.users.groups.update.strict',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_update'],
                'uses' => 'Users\\HCGroupsController@apiUpdateStrict',
            ]);
            Route::post('duplicate', [
                'as' => 'api.v1.routes.users.groups.duplicate.single',
                'middleware' => [
                    'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_list',
                    'acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_create',
                ],
                'uses' => 'Users\\HCGroupsController@apiDuplicate',
            ]);
            Route::delete('force', [
                'as' => 'api.v1.routes.users.groups.force.single',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_routes_users_groups_force_delete'],
                'uses' => 'Users\\HCGroupsController@apiForceDelete',
            ]);
        });
    });
});