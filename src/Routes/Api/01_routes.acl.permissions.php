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
    Route::group(['prefix' => 'v1/users/permissions'], function() {
        Route::get('/', [
            'as' => 'api.v1.acl.permissions',
            'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'],
            'uses' => 'Acl\\PermissionsController@apiIndexPaginate',
        ]);

        Route::group(['prefix' => 'list'], function() {
            Route::get('/', [
                'as' => 'api.v1.acl.permissions.list',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'],
                'uses' => 'Acl\\PermissionsController@apiIndex',
            ]);
            Route::get('{timestamp}', [
                'as' => 'api.v1.acl.permissions.list.update',
                'middleware' => ['acl-apps:interactivesolutions_honeycomb_acl_acl_permissions_list'],
                'uses' => 'Acl\\PermissionsController@apiIndexSync',
            ]);
        });
    });
});
