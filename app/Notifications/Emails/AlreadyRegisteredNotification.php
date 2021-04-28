<?php

namespace App\Notifications\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class AlreadyRegisteredNotification extends Notification
{
    use Queueable;

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

        $password_forgotten_url = url(route('password.api.reset', [
            'token' => app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($notifiable),
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(trans('auth.Registration attempt'))
            ->line(trans('auth.You are receiving this email because someone tried to register with this email.'))
            ->line(trans('auth.Since you already have an account here is a link to the login view.'))
            ->action(trans('auth.Connection'), $url)
            ->line(trans('auth.forgotten', ['link' => $password_forgotten_url ]))
            ->line(trans('auth.If the attempt does not comes from you, no further action is required.'))
            ->line(Lang::get('Merci pour votre confiance ! '));
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
