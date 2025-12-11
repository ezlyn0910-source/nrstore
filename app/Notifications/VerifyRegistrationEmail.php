<?php

namespace App\Notifications;

use App\Models\TempUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyRegistrationEmail extends Notification
{
    use Queueable;

    protected $tempUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(TempUser $tempUser)
    {
        $this->tempUser = $tempUser;
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
        $verificationUrl = url('/register/verify/' . $this->tempUser->token);

        return (new MailMessage)
            ->subject('Verify Your Email - NR Store')
            ->greeting('Hello ' . $this->tempUser->name . '!')
            ->line('Thank you for registering with NR Store.')
            ->line('Please click the button below to verify your email address and complete your registration.')
            ->action('Verify Email & Create Account', $verificationUrl)
            ->line('This verification link will expire in 24 hours.')
            ->line('If you did not create an account, no further action is required.');
    }
}
