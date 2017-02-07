<?php

namespace interactivesolutions\honeycombacl\models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use interactivesolutions\honeycombacl\models\traits\UserRoles;
use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsers extends HCUuidModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, UserRoles;
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
    protected $fillable = ['id', 'activated_at', 'last_login', 'last_visited', 'last_activity', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

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

    /**
     * Update last activity timestamp
     *
     * @param null $time
     */
    public function updateLastActivity($time = null)
    {
        $this->last_activity = $time ? $time : $this->freshTimestamp();
        $this->save();
    }

}
