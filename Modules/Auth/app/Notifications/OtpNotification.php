<?php

namespace Modules\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $otp)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your OTP Code')
            ->line('Your OTP code is: ' . $this->otp)
            ->line('This code will expire in 5 minutes.')
            ->line('If you did not request this code, please ignore this email.');
    }

    public function toArray($notifiable): array
    {
        return [
            'otp' => $this->otp,
        ];
    }
} 