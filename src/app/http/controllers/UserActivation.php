<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use Carbon\Carbon;
use DB;
use interactivesolutions\honeycombacl\app\models\HCUsers;
use Mail;

class UserActivation
{
    /**
     * Activations table
     *
     * @var string
     */
    protected $table = 'hc_users_activations';

    /**
     * Number of hours that needs to pass before we send a now activation email but only if user request it
     *
     * @var int
     */
    protected $resendAfter = 24;

    /**
     * Mail message
     *
     * @var
     */
    protected $mailMessage;

    /**
     * Send activation mail
     *
     * @param $user
     * @return array
     */
    public function sendActivationMail($user)
    {
        if( ! $this->shouldSend($user) ) {
            return trans('HCACL::users.activation.check_email');
        }

        $token = $this->createActivation($user);

        $this->sendMail($user, $token);

        return trans('HCACL::users.activation.resent_activation');
    }

    /**
     * Activate user
     *
     * @param $token
     * @return null
     * @throws \Exception
     */
    public function activateUser($token)
    {
        $activation = $this->getActivationByToken($token);

        if( $activation === null ) {
            throw new \Exception(trans('HCACL::users.activation.bad_token'));
        }

        $user = HCUsers::findOrFail($activation->user_id);

        if( is_null($user) )
            throw new \Exception(trans('HCACL::users.activation.user_not_found'));

        // activate user
        $user->activate();

        // delete activation code
        $this->deleteActivation($token);

        // login user to the site
        auth()->login($user);
    }

    /**
     * Check if activation mail should be resent
     *
     * @param $user
     * @return bool
     */
    protected function shouldSend($user)
    {
        $activation = $this->getActivation($user);

        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }

    /**
     * Get token
     *
     * @return string
     */
    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * Create activation
     *
     * @param $user
     * @return string
     */
    public function createActivation($user)
    {
        $activation = $this->getActivation($user);

        if( ! $activation ) {
            return $this->createToken($user);
        }

        return $this->regenerateToken($user);
    }

    /**
     * Regenerate token
     *
     * @param $user
     * @return string
     */
    protected function regenerateToken($user)
    {
        $token = $this->getToken();

        DB::table($this->table)->where('user_id', $user->id)->update([
            'token'      => $token,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $token;
    }

    /**
     * Create token
     *
     * @param $user
     * @return string
     */
    protected function createToken($user)
    {
        $token = $this->getToken();

        DB::table($this->table)->insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $token;
    }

    /**
     * Get activation
     *
     * @param $user
     * @return mixed|static
     */
    public function getActivation($user)
    {
        return DB::table($this->table)->where('user_id', $user->id)->first();
    }

    /**
     * Get activation by token
     *
     * @param $token
     * @return mixed|static
     */
    public function getActivationByToken($token)
    {
        return DB::table($this->table)->where('token', $token)->first();
    }

    /**
     * Delete activation
     *
     * @param $token
     */
    public function deleteActivation($token)
    {
        DB::table($this->table)->where('token', $token)->delete();
    }

    /**
     * @param $user
     * @param $token
     */
    protected function sendMail($user, $token)
    {
        $message = [
            'ocEmail' => $user->email,
            'link'    => route('auth.activation', $token),
        ];

        if( $this->mailMessage )
            $message['custom'] = $this->mailMessage;

        \Mail::send('HCACL::emails.activation', $message, function ($mail) use ($user) {
            $mail->from('admin@interactivesolutions.lt', trans('HCACL::users.activation.mail.from'));
            $mail->to($user->email)->subject(trans('HCACL::users.activation.mail.subject'));
        });
    }

    /**
     * @param mixed $mailMessage
     */
    public function setMailMessage($mailMessage)
    {
        $this->mailMessage = $mailMessage;
    }
}