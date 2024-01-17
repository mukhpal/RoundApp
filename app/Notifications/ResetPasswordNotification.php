<?php


namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends ResetPassword
{

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = $this->createUrl($notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->markdown('mail.user.reset-password', [
                'url' => $url
            ]);
    }

    /**
     * @param $notifiable
     * @return string
     */
    public function createUrl($notifiable)
    {
        $params = [
            '{token}' => $this->token,
            '{email}' => $notifiable->getEmailForPasswordReset()
        ];
        $url = config('auth.password_reset_url');
        return strtr($url, $params);
    }
}
