<?php

namespace interactivesolutions\honeycombacl\models\acl;

use interactivesolutions\honeycombcore\models\HCModel;

class RolesUsersConnections extends HCModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_acl_roles_users_connections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'user_id'];
}
