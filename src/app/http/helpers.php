<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use interactivesolutions\honeycombacl\models\acl\Permissions;

if (!function_exists ('getHCPermissions')) {
    /**
     * @param bool $forceReCache
     * @internal param bool $force
     */
    function getHCPermissions (bool $forceReCache = false)
    {
        if ($forceReCache || !Cache::has('hc-permissions'))
        {
            $expiresAt = Carbon::now ()->addHour (12);
            $permissions = getPermissions ();

            Cache::put ('hc-permissions', $permissions, $expiresAt);
        }

        return Cache::get ('hc-permissions');
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    function getPermissions ()
    {
        try {
            if( class_exists(Permissions::class) ) {
                if( Schema::hasTable(Permissions::getTableName()) ) {
                    return Permissions::with('roles')->get();
                }
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage ();

            if ($e->getCode () != 1045)
                throw new \Exception($msg);
        }
    }
}
