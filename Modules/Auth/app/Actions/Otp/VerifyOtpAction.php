<?php

namespace Modules\Auth\Actions\Otp;

use Illuminate\Support\Facades\Cache;

class VerifyOtpAction
{
    public function execute(string $identifier, string $otp): bool
    {
        $key = "otp_{$identifier}";
        $storedOtp = Cache::get($key);
        
        if (!$storedOtp) {
            return false;
        }
        
        if ($storedOtp === $otp) {
            Cache::forget($key);
            return true;
        }
        
        return false;
    }

    public function verifyForEmail(string $email, string $otp): bool
    {
        return $this->execute($email, $otp);
    }

    public function verifyForPhone(string $phone, string $otp): bool
    {
        return $this->execute($phone, $otp);
    }
} 