<?php

namespace interactivesolutions\honeycombacl\models;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsersPasswords extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users_passwords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'password'];

}
