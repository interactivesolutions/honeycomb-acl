<?php

namespace interactivesolutions\honeycombacl\app\models\acl;

use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombcore\models\HCUuidModel;

class Roles extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','slug'];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, RolesPermissionsConnections::getTableName(), 'role_id', 'permission_id');
    }

    /**
     * Grant the given permission to a role.
     *
     * @param Permissions $permission
     * @return mixed
     */
    public function givePermissionTo(Permissions $permission)
    {
        return $this->permissions()->save($permission);
    }

    /**
     * A role may be given various users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(HCUsers::class, RolesUsersConnections::getTableName(), 'role_id', 'user_id');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', 'super-admin');
    }

    /**
     * Get super admin
     *
     * @param $query
     */
    public function scopeNotSuperAdmin($query)
    {
        return $query->select('id', 'slug', 'name')->where('slug', '!=', 'super-admin');
    }

}
