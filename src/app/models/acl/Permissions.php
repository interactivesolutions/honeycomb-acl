<?php

namespace interactivesolutions\honeycombacl\app\models\acl;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class Permissions extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'controller', 'action'];

    /**
     * Deleting permission
     *
     * @param $action
     */
    public static function deletePermission(string $action)
    {
        $permission = Permissions::where('action', $action)->first();
        RolesPermissionsConnections::where('permission_id', $permission->id)->forceDelete();
        $permission->forceDelete();
    }

    /**
     * A permission can be applied to roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class, RolesPermissionsConnections::getTableName(), 'permission_id', 'role_id');
    }

    /**
     * Delete permission with related connection in roles permissions tables
     *
     * @param $name
     * @param $action
     */
    public static function permissionDelete($name, $action)
    {
        $permission = Permissions::where('name', $name)
            ->where('action', $action)
            ->first();

        if( ! is_null($permission) ) {
            RolesPermissionsConnections::where('permission_id', $permission->id)->delete();

            $permission->forceDelete();
        }
    }

    /**
     * Get name attribute
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return $this->attributes['name'] = trans($value);
    }
}
