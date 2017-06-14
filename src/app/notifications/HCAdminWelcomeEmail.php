<?php

namespace interactivesolutions\honeycombacl\app\notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HCAdminWelcomeEmail extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $authRoute;

    /**
     * Send password holder
     *
     * @var
     */
    private $sendPassword;

    /**
     * Create a notification instance.
     *
     * @param $authRoute
     */
    public function __construct(string $authRoute = 'auth.index')
    {
        $this->authRoute = $authRoute;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->view('HCACL::emails.template')
            ->subject(trans('HCACL::users.welcome_email.subject'))
            ->greeting(trans('HCACL::users.welcome_email.greeting'))
            ->line(trans('HCACL::users.welcome_email.text'))
            ->line(trans('HCACL::users.welcome_email.show_email', ['email' => $notifiable->email]));

        if( $this->sendPassword ) {
            $message->line(trans('HCACL::users.welcome_email.show_password', ['password' => $this->sendPassword]));
        }

        $message->action(trans('HCACL::users.welcome_email.login_link'), route($this->authRoute));

        if( is_null($notifiable->activated_at) ) {
            $message->line(trans('HCACL::users.welcome_email.activation_required'));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Sent reset password
     *
     * @param string $password
     * @return $this
     */
    public function withPassword(string $password)
    {
        $this->sendPassword = $password;

        return $this;
    }
}
