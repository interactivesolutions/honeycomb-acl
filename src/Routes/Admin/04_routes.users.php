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
    Route::get('users', [
        'as' => 'admin.users.index',
        'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'],
        'uses' => 'HCUsersController@adminIndex',
    ]);

    Route::group(['prefix' => 'api/users'], function() {
        Route::get('/', [
            'as' => 'admin.api.users',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'],
            'uses' => 'HCUsersController@apiIndexPaginate',
        ]);
        Route::post('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'],
            'uses' => 'HCUsersController@apiStore',
        ]);
        Route::delete('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'],
            'uses' => 'HCUsersController@apiDestroy',
        ]);

        Route::get('list', [
            'as' => 'admin.api.users.list',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'],
            'uses' => 'HCUsersController@apiIndex',
        ]);
        Route::post('restore', [
            'as' => 'admin.api.users.restore',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'],
            'uses' => 'HCUsersController@apiRestore',
        ]);
        Route::post('merge', [
            'as' => 'admin.api.users.merge',
            'middleware' => [
                'acl:interactivesolutions_honeycomb_acl_users_create',
                'acl:interactivesolutions_honeycomb_acl_users_delete',
            ],
            'uses' => 'HCUsersController@apiMerge',
        ]);
        Route::delete('force', [
            'as' => 'admin.api.users.force.multi',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'],
            'uses' => 'HCUsersController@apiForceDelete',
        ]);

        Route::group(['prefix' => '{id}'], function() {

            Route::get('/', [
                'as' => 'admin.api.users.single',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_list'],
                'uses' => 'HCUsersController@apiShow',
            ]);
            Route::put('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'],
                'uses' => 'HCUsersController@apiUpdate',
            ]);
            Route::delete('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_delete'],
                'uses' => 'HCUsersController@apiDestroy',
            ]);

            Route::put('strict', [
                'as' => 'admin.api.users.update.strict',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_update'],
                'uses' => 'HCUsersController@apiUpdateStrict',
            ]);
            Route::post('duplicate', [
                'as' => 'admin.api.users.duplicate',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_create'],
                'uses' => 'HCUsersController@apiDuplicate',
            ]);
            Route::delete('force', [
                'as' => 'admin.api.users.force',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_users_force_delete'],
                'uses' => 'HCUsersController@apiForceDelete',
            ]);
        });
    });
});
