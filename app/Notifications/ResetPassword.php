<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    protected $token; // Add a protected property for the token

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token; // Assign the token to the property
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }
    public function toMail($notifiable)
    {
        $resetUrl = config('app.url') . '/api/reset-password?token=' . $this->token;

        // return (new MailMessage)
        //     ->subject('Reset Your Password')
        //     ->line('You are receiving this email because we received a password reset request for your account.')
        //     ->action('Reset Password', $url)
        //     ->line('If you did not request a password reset, no further action is required.');

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Reset Token: ' . $this->token) // Include the token here
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
