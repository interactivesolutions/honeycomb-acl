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
    Route::group(['prefix' => 'v1/users'], function() {
        Route::get('/', [
            'as' => 'api.v1.users',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'],
            'uses' => 'HCUsersController@apiIndexPaginate',
        ]);
        Route::post('/', [
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_create'],
            'uses' => 'HCUsersController@apiStore',
        ]);
        Route::delete('/', [
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_delete'],
            'uses' => 'HCUsersController@apiDestroy',
        ]);


        Route::group(['prefix' => 'list'], function() {
            Route::get('/', [
                'as' => 'api.v1.users.list',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'],
                'uses' => 'HCUsersController@apiIndex',
            ]);
            Route::get('{timestamp}', [
                'as' => 'api.v1.users.list.update',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'],
                'uses' => 'HCUsersController@apiIndexSync',
            ]);
        });

        Route::post('restore', [
            'as' => 'api.v1.users.restore',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'],
            'uses' => 'HCUsersController@apiRestore',
        ]);
        Route::post('merge', [
            'as' => 'api.v1.users.merge',
            'middleware' => [
                'acl-apps:interactivesolutions_honeycomb_acl_users_create',
                'acl-apps:interactivesolutions_honeycomb_acl_users_update',
            ],
            'uses' => 'HCUsersController@apiMerge',
        ]);
        Route::delete('force', [
            'as' => 'api.v1.users.force.multi',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_force_delete'],
            'uses' => 'HCUsersController@apiForceDelete',
        ]);

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/', [
                'as' => 'api.v1.users.single',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_list'],
                'uses' => 'HCUsersController@apiShow',
            ]);
            Route::put('/', [
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'],
                'uses' => 'HCUsersController@apiUpdate',
            ]);
            Route::delete('/', [
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_delete'],
                'uses' => 'HCUsersController@apiDestroy',
            ]);

            Route::put('strict', [
                'as' => 'admin.api.users.update.strict',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_update'],
                'uses' => 'HCUsersController@apiUpdateStrict',
            ]);
            Route::post('duplicate', [
                'as' => 'api.v1.users.duplicate',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_create'],
                'uses' => 'HCUsersController@apiDuplicate',
            ]);
            Route::delete('force', [
                'as' => 'api.v1.users.force',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_users_force_delete'],
                'uses' => 'HCUsersController@apiForceDelete',
            ]);
        });
    });
});
