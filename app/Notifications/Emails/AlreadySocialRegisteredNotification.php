<?php

namespace App\Notifications\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class AlreadySocialRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * The social provider.
     *
     * @var string
     */
    public $provider;

    /**
     * Create a notification instance.
     *
     * @param  string  $proviedr
     * @return void
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('app.login');
        return (new MailMessage)
            ->subject(trans('auth.Registration attempt'))
            ->line(trans('auth.You are receiving this email because someone tried to register with this email.'))
            ->line(trans('auth.Since you already have an account here is a link to the login view.'))
            ->line(trans('auth.Your account is linked to :provider', ['provider' => $this->provider]))
            ->action(trans('auth.Connection'), $url)
            ->line(trans('auth.If you do not have access to your social account, you can still ask for a password reset to set a password to your account.'))
            ->line(trans('auth.If the attempt does not comes from you, no further action is required.'));
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
