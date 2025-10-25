<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetByAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public string $temporaryPassword)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'GymMate');
        return (new MailMessage)
            ->subject("{$appName}: Your password has been reset by an administrator")
            ->greeting('Password Reset')
            ->line('An administrator has reset your account password.')
            ->line('Email: ' . $notifiable->email)
            ->line('Temporary Password: ' . $this->temporaryPassword)
            ->action('Sign in', url('/login'))
            ->line('For security, please change this password immediately after logging in.');
    }
}
