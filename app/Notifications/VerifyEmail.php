<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }
        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->markdown('mail.user.verify', [
            'url' => $verificationUrl
        ]);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $regex = "/^https?:\/\/[^\/]+\/api\/auth\/email\/verify\/(?<id>[0-9]+)\/(?<hash>[^\/]+)\?expires=(?<expires>[0-9]+)&signature=(?<signature>.+)$/";
        $url = parent::verificationUrl($notifiable);
        preg_match($regex, $url, $matches);
        $params = [
            '{id}' => $matches['id'],
            '{hash}' => $matches['hash'],
            '{expires}' => $matches['expires'],
            '{signature}' => $matches['signature']
        ];
        $verificationUrl = config('auth.email_verification_url');
        return strtr($verificationUrl, $params);
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
