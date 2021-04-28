<?php

namespace App\Notifications\Emails;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyApiEmail extends VerifyEmail
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verificationapi.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get("Vérification d'adresse e-mail"))
            ->line(Lang::get('Merci de votre inscription sur notre application Addictives.'))
            ->line(Lang::get('Il ne vous reste plus qu’à vérifier votre e-mail en cliquant sur le bouton ci-dessous pour finaliser votre inscription et profiter de l’application : '))
            ->action(Lang::get('Vérifier mon adresse e-mail'), $url)
            ->line(Lang::get('Merci de votre confiance !'))
            ->line(Lang::get("Si vous n’êtes pas à l’origine de la demande de création de compte sur l’application Addictives, vous pouvez ignorer cet e-mail."));
    }
}
