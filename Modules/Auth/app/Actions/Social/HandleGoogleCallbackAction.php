<?php

namespace Modules\Auth\Actions\Social;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HandleGoogleCallbackAction
{
    public function execute(array $googleUserData): User
    {
        $user = User::where('email', $googleUserData['email'])->first();
        
        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $googleUserData['name'],
                'email' => $googleUserData['email'],
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(), // Google users are pre-verified
                'google_id' => $googleUserData['google_id'] ?? null,
            ]);
        } else {
            // Update existing user with Google ID if not set
            if (!$user->google_id && isset($googleUserData['google_id'])) {
                $user->update(['google_id' => $googleUserData['google_id']]);
            }
        }
        
        return $user;
    }
} 