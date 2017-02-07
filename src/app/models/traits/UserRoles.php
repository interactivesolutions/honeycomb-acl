<?php

namespace interactivesolutions\honeycombacl\models\traits;


use interactivesolutions\honeycombacl\models\acl\Permissions;
use interactivesolutions\honeycombacl\models\acl\Roles;
use interactivesolutions\honeycombacl\models\acl\RolesUsersConnections;

trait UserRoles
{
    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class, RolesUsersConnections::getTableName(), 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Roles::whereSlug($role)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if( is_string($role) ) {
            return $this->roles->contains('slug', $role);
        }

        foreach ( $role as $r ) {
            if( is_string($r) ) {
                if( $this->hasRole($r) ) {
                    return true;
                }
            } else if( $this->hasRole($r->slug) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param Permissions $permission
     * @return bool
     */
    public function hasPermission(Permissions $permission)
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * Checking if user if super admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Checking if user if super admin
     *
     * @return bool
     */
    public function isNotSuperAdmin()
    {
        return ! $this->isSuperAdmin();
    }

    /**
     * Get all roles as string
     *
     * @return mixed
     */
    public function roleNames()
    {
        return $this->roles()->lists('name')->implode(', ');
    }

    /**
     * Get all roles as slugs array
     *
     * @return mixed
     */
    public function currentRolesArray()
    {
        return $this->roles()->lists('slug');
    }
}