<?php

namespace interactivesolutions\honeycombacl\models;

use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsers extends HCUuidModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hc_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'activated_at', 'remember_token', 'last_login', 'last_visited', 'last_activity'];

    /**
     * Update last login timestamp
     *
     * @param null $time
     */
    public function updateLastLogin($time = null)
    {
        $this->last_login = $time ? $time : $this->freshTimestamp();
        $this->save();
    }

}