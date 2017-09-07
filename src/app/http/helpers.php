<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use interactivesolutions\honeycombacl\app\models\acl\Permissions;

if( ! function_exists('getHCPermissions') ) {
    /**
     * @param bool $forceReCache
     * @internal param bool $force
     */
    function getHCPermissions(bool $forceReCache = false)
    {
        if( $forceReCache || ! Cache::has('hc-permissions') ) {
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
            if( class_exists(Permissions::class) ) {
                if( Schema::hasTable(Permissions::getTableName()) ) {
                    return Permissions::with('roles')->get();
                }
            }
        } catch ( \Exception $e ) {
            $msg = $e->getMessage();

            if( $e->getCode() != 1045 )
                throw new \Exception($msg);
        }
    }
}

if( ! function_exists('createHCUser') ) {

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
    function createHCUser(string $email, array $roleIds, bool $active = true, string $password = null, array $additionalData = [], $sendWelcomeEmail = true, bool $sendPassword = false)
    {
        DB::beginTransaction();

        try {
            $password = $password ? $password : random_str(10);

            // create user
            $record = \interactivesolutions\honeycombacl\app\models\HCUsers::create([
                    "email"        => $email,
                    "password"     => bcrypt($password),
                    "activated_at" => $active ? Carbon::now()->toDateTimeString() : null,
                ] + $additionalData
            );

            // create user roles
            if( empty($roleIds) ) {
                $record->roleMember();
            } else {
                $record->assignRoles($roleIds);
            }

            // send welcome email
            if( $sendWelcomeEmail || $sendPassword ) {
                if( $sendPassword ) {
                    $record->sendWelcomeEmailWithPassword($password);
                } else {
                    $record->sendWelcomeEmail();
                }
            }

            // create user activation
            if( is_null($record->activated_at) ) {
                $record->createTokenAndSendActivationCode();
            }

        } catch ( \Exception $e ) {
            DB::rollBack();

            throw new \Exception($e);
        }

        DB::commit();

        return $record;
    }
}