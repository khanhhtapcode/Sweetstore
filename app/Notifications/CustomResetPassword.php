<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Services\EmailService;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['custom'];
    }

    /**
     * Send the notification using PHPMailer
     */
    public function toCustom($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $emailService = new EmailService();

        return $emailService->sendResetPasswordEmail(
            $notifiable->email,
            $notifiable->name,
            $resetUrl
        );
    }
}
