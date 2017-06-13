<?php

namespace interactivesolutions\honeycombacl\app\models\traits;

use interactivesolutions\honeycombacl\app\http\controllers\UserActivation;
use interactivesolutions\honeycombacl\app\notifications\HCActivationLink;

trait ActivateUser
{
    /**
     * Check if user is activated
     *
     * @return bool
     */
    public function isActivated()
    {
        return ! ! $this->activated_at;
    }

    /**
     * Check if user is not activated
     *
     * @return bool
     */
    public function isNotActivated()
    {
        return ! $this->isActivated();
    }

    /**
     * Create and send user activation
     */
    public function createTokenAndSendActivationCode()
    {
        (new UserActivation())->sendActivationMail($this);
    }

    /**
     * Send the activation link notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendActivationLinkNotification($token)
    {
        $this->notify(new HCActivationLink($token));
    }

    /**
     * Activate account
     */
    public function activate()
    {
        $this->activated_at = $this->freshTimestamp();
        $this->save();
    }
}