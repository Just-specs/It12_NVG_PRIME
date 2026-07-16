<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginVerificationCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $expiresInMinutes = 10
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your NVG Prime Movers login verification code')
            ->greeting('Login verification required')
            ->line('Use the verification code below to complete your login to the Dispatch Management System.')
            ->line('Verification code: ' . $this->code)
            ->line('This code expires in ' . $this->expiresInMinutes . ' minutes.')
            ->line('If you did not try to log in, you can safely ignore this email.');
    }
}
