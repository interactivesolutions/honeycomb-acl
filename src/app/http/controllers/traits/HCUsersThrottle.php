<?php

namespace interactivesolutions\honeycombacl\app\http\controllers\traits;

use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use OCLog;

trait HCUsersThrottle
{

    use ThrottlesLogins;

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse (Request $request)
    {


        $seconds = app (RateLimiter::class)->availableIn (
            $this->getThrottleKey ($request)
        );

        return OCLog::error ('AUTH-003', trans ('users::users.errors.to_many_attempts', ['seconds' => $seconds]));
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginTrait ()
    {
        return in_array (ThrottlesLogins::class, class_uses_recursive (get_class ($this)));
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername ()
    {
        return property_exists ($this, 'username') ? $this->username : 'email';
    }
}