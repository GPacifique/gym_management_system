<?php

namespace App\Notifications;

use App\Models\Gym;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class GymEmailVerification extends Notification implements ShouldQueue
{
    use Queueable;

    public $gym;

    /**
     * Create a new notification instance.
     */
    public function __construct(Gym $gym)
    {
        $this->gym = $gym;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl();

        return (new MailMessage)
            ->subject('Verify Your Gym Email Address')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering your gym "' . $this->gym->name . '" with us.')
            ->line('Please click the button below to verify your email address and activate your gym account.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 24 hours.')
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * Get the verification URL for the given gym.
     */
    protected function verificationUrl(): string
    {
        return URL::temporarySignedRoute(
            'gym.verify.email',
            now()->addHours(24),
            [
                'gym' => $this->gym->id,
                'hash' => sha1($this->gym->email),
            ]
        );
    }
}
