<?php

namespace interactivesolutions\honeycombacl\models\acl;

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
    public static function deletePermission($action)
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
}
