<?php

namespace interactivesolutions\honeycombacl\models\acl;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class RolesUsersConnections extends HCUuidModel
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
