<?php

namespace interactivesolutions\honeycombacl\app\models;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsersEmails extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'email', 'recovery'];

}
