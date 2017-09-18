<?php

namespace interactivesolutions\honeycombacl\app\models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use interactivesolutions\honeycombacl\app\models\traits\ActivateUser;
use interactivesolutions\honeycombacl\app\models\traits\UserRoles;
use interactivesolutions\honeycombacl\app\notifications\HCAdminWelcomeEmail;
use interactivesolutions\honeycombacl\app\notifications\HCResetPassword;
use interactivesolutions\honeycombcore\models\HCUuidModel;

class HCUsers extends HCUuidModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, UserRoles, ActivateUser;

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
     * @param null|string $time
     */
    public function updateLastLogin(string $time = null)
    {
        $this->timestamps = false;
        $this->last_login = $time ? $time : $this->freshTimestamp();
        $this->save();

        $this->updateLastActivity();
    }

    /**
     * Update last activity timestamp
     *
     * @param null|string $time
     */
    public function updateLastActivity(string $time = null)
    {
        $this->timestamps = false;
        $this->last_activity = $time ? $time : $this->freshTimestamp();
        $this->save();
    }

    /**
     * Override default password notification sending mail template
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new HCResetPassword($token));
    }

    /**
     * Welcome email
     */
    public function sendWelcomeEmail()
    {
        $this->notify((new HCAdminWelcomeEmail()));
    }

    /**
     * Welcome email with password
     *
     * @param string $password
     */
    public function sendWelcomeEmailWithPassword(string $password)
    {
        $this->notify(
            (new HCAdminWelcomeEmail())
                ->withPassword($password)
        );
    }
}
