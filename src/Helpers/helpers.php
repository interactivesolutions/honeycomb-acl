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

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use InteractiveSolutions\HoneycombAcl\Models\Acl\Permissions;

if (!function_exists('getHCPermissions')) {
    /**
     * @param bool $forceReCache
     * @return mixed
     * @throws Exception
     */
    function getHCPermissions(bool $forceReCache = false)
    {
        if ($forceReCache || !Cache::has('hc-permissions')) {
            $expiresAt = Carbon::now()->addHour(12);
            $permissions = getPermissions();

            Cache::put('hc-permissions', $permissions, $expiresAt);
        }

        return Cache::get('hc-permissions');
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    function getPermissions()
    {
        try {
            if (class_exists(Permissions::class)) {
                if (Schema::hasTable(Permissions::getTableName())) {
                    return Permissions::with('roles')->get();
                }
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            if ($e->getCode() != 1045) {
                throw new \Exception($msg);
            }
        }
    }
}

if (!function_exists('createHCUser')) {

    /**
     * Create user account
     *
     * @param string $email
     * @param array $roleIds - array of role ids
     * @param bool $active
     * @param string|null $password
     * @param array $additionalData
     * @param bool $sendWelcomeEmail
     * @param bool $sendPassword
     * @return static
     * @throws Exception
     */
    function createHCUser(
        string $email,
        array $roleIds,
        bool $active = true,
        string $password = null,
        array $additionalData = [],
        $sendWelcomeEmail = true,
        bool $sendPassword = false
    ) {
        DB::beginTransaction();

        try {
            $password = $password ? $password : random_str(10);

            // create user
            $record = \InteractiveSolutions\HoneycombAcl\Models\HCUsers::create([
                    "email" => $email,
                    "password" => bcrypt($password),
                    "activated_at" => $active ? Carbon::now()->toDateTimeString() : null,
                ] + $additionalData
            );

            // create user roles
            if (empty($roleIds)) {
                $record->roleMember();
            } else {
                $record->assignRoles($roleIds);
            }

            // send welcome email
            if ($sendWelcomeEmail || $sendPassword) {
                if ($sendPassword) {
                    $record->sendWelcomeEmailWithPassword($password);
                } else {
                    $record->sendWelcomeEmail();
                }
            }

            // create user activation
            if (is_null($record->activated_at)) {
                $record->createTokenAndSendActivationCode();
            }

        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e);
        }

        DB::commit();

        return $record;
    }
}