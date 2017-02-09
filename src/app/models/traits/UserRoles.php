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

    /**
     * Attach user role to user
     */
    public function roleUser ()
    {
        RolesUsersConnections::create (['user_id' => $this-id, 'role_id' => Roles::where ('slug', 'user')->first ()['id']]);
    }

    /**
     * Attach super admin role to user
     */
    public function roleSuperAdmin ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'super-admin')->first ()['id']]);
    }

    /**
     * Attach project admin role to user
     */
    public function roleProjectAdmin ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'project-admin')->first ()['id']]);
    }

    /**
     * Attach editor role to user
     */
    public function roleEditor ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'editor')->first ()['id']]);
    }

    /**
     * Attach author role to user
     */
    public function roleAuthor ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'author')->first ()['id']]);
    }

    /**
     * Attach contributor role to user
     */
    public function roleContributor ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'contributor')->first ()['id']]);
    }

    /**
     * Attach moderator role to user
     */
    public function roleModerator ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'moderator')->first ()['id']]);
    }

    /**
     * Attach member role to user
     */
    public function roleMember ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'subscriber')->first ()['id']]);
    }

    /**
     * Attach subscriber role to user
     */
    public function roleSubscriber ()
    {
        RolesUsersConnections::create (['user_id' => $this->id, 'role_id' => Roles::where ('slug', 'subscriber')->first ()['id']]);
    }
}