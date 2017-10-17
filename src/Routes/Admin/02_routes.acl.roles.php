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
    Route::get('users/roles', [
        'as' => 'admin.acl.roles.index',
        'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'],
        'uses' => 'Acl\\RolesController@adminIndex',
    ]);

    Route::group(['prefix' => 'api/users/roles'], function() {
        Route::get('/', [
            'as' => 'admin.api.acl.roles',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'],
            'uses' => 'Acl\\RolesController@apiIndexPaginate',
        ]);
        Route::post('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'],
            'uses' => 'Acl\\RolesController@apiStore',
        ]);
        Route::delete('/', [
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'],
            'uses' => 'Acl\\RolesController@apiDestroy',
        ]);

        Route::get('list', [
            'as' => 'admin.api.acl.roles.list',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'],
            'uses' => 'Acl\\RolesController@apiIndex',
        ]);
        Route::post('restore', [
            'as' => 'admin.api.acl.roles.restore',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'],
            'uses' => 'Acl\\RolesController@apiRestore',
        ]);
        Route::post('merge', [
            'as' => 'admin.api.acl.roles.merge',
            'middleware' => [
                'acl:interactivesolutions_honeycomb_acl_acl_roles_create',
                'acl:interactivesolutions_honeycomb_acl_acl_roles_update',
            ],
            'uses' => 'acl\\RolesController@apiMerge',
        ]);
        Route::delete('force', [
            'as' => 'admin.api.acl.roles.force.multi',
            'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'],
            'uses' => 'Acl\\RolesController@apiForceDelete',
        ]);

        Route::group(['prefix' => '{id}'], function() {
            Route::get('/', [
                'as' => 'admin.api.acl.roles.single',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_list'],
                'uses' => 'Acl\\RolesController@apiShow',
            ]);
            Route::put('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'],
                'uses' => 'Acl\\RolesController@apiUpdate',
            ]);
            Route::delete('/', [
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_delete'],
                'uses' => 'Acl\\RolesController@apiDestroy',
            ]);

            Route::put('strict', [
                'as' => 'admin.api.acl.roles.update.strict',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_update'],
                'uses' => 'Acl\\RolesController@apiUpdateStrict',
            ]);
            Route::post('duplicate', [
                'as' => 'admin.api.acl.roles.duplicate',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_create'],
                'uses' => 'Acl\\RolesController@apiDuplicate',
            ]);
            Route::delete('force', [
                'as' => 'admin.api.acl.roles.force',
                'middleware' => ['acl:interactivesolutions_honeycomb_acl_acl_roles_force_delete'],
                'uses' => 'Acl\\RolesController@apiForceDelete',
            ]);
        });
    });
});
