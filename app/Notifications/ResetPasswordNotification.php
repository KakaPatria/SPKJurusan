<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
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
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // generate 6-digit numeric code and store it for inline token flow
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \Illuminate\Support\Facades\DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $notifiable->getEmailForPasswordReset()],
            ['code' => $code, 'created_at' => now()]
        );

        return (new MailMessage)
            ->subject('🔑 Reset Password - Sistem Pemilihan Jurusan Polije')
            ->view('emails.reset-password', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'code' => $code,
                'token' => $this->token,
                'expiresIn' => config('auth.passwords.users.expire', 60),
            ]);
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
