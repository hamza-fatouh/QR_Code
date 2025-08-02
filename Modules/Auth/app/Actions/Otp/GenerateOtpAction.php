<?php

namespace Modules\Auth\Actions\Otp;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GenerateOtpAction
{
    public function execute(string $identifier, int $expiryMinutes = 5): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $key = "otp_{$identifier}";
        
        Cache::put($key, $otp, now()->addMinutes($expiryMinutes));
        
        return $otp;
    }

    public function generateForEmail(string $email): string
    {
        return $this->execute($email);
    }

    public function generateForPhone(string $phone): string
    {
        return $this->execute($phone);
    }
} 