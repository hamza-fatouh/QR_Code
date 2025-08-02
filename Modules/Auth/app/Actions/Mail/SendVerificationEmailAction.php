<?php

namespace Modules\Auth\Actions\Mail;

use App\Models\User;
use Illuminate\Support\Facades\URL;

class SendVerificationEmailAction
{
    public function execute(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $user->sendEmailVerificationNotification($verificationUrl);
    }

    public function sendPasswordResetEmail(User $user, string $token): void
    {
        $resetUrl = url("/reset-password?token={$token}&email={$user->email}");
        
        $user->notify(new \Modules\Auth\Notifications\PasswordResetNotification($resetUrl));
    }
} 