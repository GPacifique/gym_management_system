<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public bool $temporaryPasswordGenerated,
        public ?string $temporaryPassword = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'GymMate');
        $mail = (new MailMessage)
            ->subject("Welcome to {$appName}")
            ->greeting('Welcome!')
            ->line("An account has been created for you on {$appName}.")
            ->line('Email: ' . $notifiable->email)
            ->action('Sign in', url('/login'))
            ->line('After signing in, please review your profile and change your password from your account settings.');

        if ($this->temporaryPasswordGenerated && $this->temporaryPassword) {
            $mail->line('Temporary Password: ' . $this->temporaryPassword)
                 ->line('For security, change this password after your first login.');
        } else {
            $mail->line('Use the password provided to you by the administrator.');
        }

        return $mail;
    }
}
