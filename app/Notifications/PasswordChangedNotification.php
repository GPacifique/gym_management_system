<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'GymMate');
        return (new MailMessage)
            ->subject("{$appName}: Your password was changed")
            ->greeting('Password Changed')
            ->line('This is a confirmation that your account password was changed.')
            ->line('If you did not initiate this change, please contact support immediately.')
            ->action('Sign in', url('/login'));
    }
}
