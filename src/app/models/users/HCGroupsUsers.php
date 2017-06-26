<?php

namespace interactivesolutions\honeycombacl\app\models\users;

use interactivesolutions\honeycombcore\models\HCModel;

class HCGroupsUsers extends HCModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users_groups_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group_id', 'user_id'];
}