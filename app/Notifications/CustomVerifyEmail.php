<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Services\EmailService;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return []; // Không dùng channel, xử lý trực tiếp
    }

    /**
     * Handle the notification directly
     */
    public function handle($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        $emailService = new EmailService();

        return $emailService->sendVerificationEmail(
            $notifiable->email,
            $notifiable->name,
            $verificationUrl
        );
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return \URL::temporarySignedRoute(
            'verification.verify',
            \Carbon\Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
