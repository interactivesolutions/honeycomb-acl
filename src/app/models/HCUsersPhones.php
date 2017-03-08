<?php

namespace interactivesolutions\honeycombacl\app\models;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsersPhones extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users_phones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'phone', 'recovery'];

}
