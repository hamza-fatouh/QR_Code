<?php

namespace Modules\Auth\Services;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Actions\Mail\SendVerificationEmailAction;
use Modules\Auth\Actions\Otp\GenerateOtpAction;
use Modules\Auth\Actions\Otp\VerifyOtpAction;
use Modules\Auth\Actions\Social\HandleGoogleCallbackAction;
use Modules\Auth\Actions\User\HashPasswordAction;
use Modules\Auth\DTOs\GoogleAuthData;
use Modules\Auth\DTOs\LoginData;
use Modules\Auth\DTOs\PasswordResetData;
use Modules\Auth\DTOs\ProfileUpdateData;
use Modules\Auth\DTOs\RegisterData;
use Modules\Auth\Interfaces\AuthRepositoryInterface;

class AuthService
{
    use ApiResponse;

    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private HashPasswordAction $hashPasswordAction,
        private GenerateOtpAction $generateOtpAction,
        private VerifyOtpAction $verifyOtpAction,
        private SendVerificationEmailAction $sendVerificationEmailAction,
        private HandleGoogleCallbackAction $handleGoogleCallbackAction
    ) {}

    public function login(LoginData $loginData): array
    {
        $user = $this->authRepository->findByEmail($loginData->email);
        
        if (!$user || !Hash::check($loginData->password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'data' => null
            ];
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];
    }

    public function register(RegisterData $registerData): array
    {
        $hashedPassword = $this->hashPasswordAction->execute($registerData->password);
        
        $user = $this->authRepository->create([
            'name' => $registerData->name,
            'email' => $registerData->email,
            'password' => $hashedPassword,
            'phone' => $registerData->phone,
        ]);

        // Send verification email
        $this->sendVerificationEmailAction->execute($user);

        $token = $user->createToken('auth-token')->plainTextToken;
        
        return [
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];
    }

    public function logout(): array
    {
        $user = Auth::user();
        $user->tokens()->delete();
        
        return [
            'success' => true,
            'message' => 'Logged out successfully',
            'data' => null
        ];
    }

    public function getProfile(): array
    {
        $user = Auth::user();
        
        return [
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => $user
        ];
    }

    public function updateProfile(ProfileUpdateData $profileData): array
    {
        $user = Auth::user();
        $updateData = [
            'name' => $profileData->name,
            'email' => $profileData->email,
            'phone' => $profileData->phone,
        ];

        if ($profileData->password) {
            $updateData['password'] = $this->hashPasswordAction->execute($profileData->password);
        }

        $this->authRepository->update($user, $updateData);
        
        return [
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ];
    }

    public function sendOtp(string $email): array
    {
        $user = $this->authRepository->findByEmail($email);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ];
        }

        $otp = $this->generateOtpAction->generateForEmail($email);
        
        // Send OTP via email/SMS (implement notification)
        $user->notify(new \Modules\Auth\Notifications\OtpNotification($otp));
        
        return [
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => null
        ];
    }

    public function verifyOtp(string $email, string $otp): array
    {
        $isValid = $this->verifyOtpAction->verifyForEmail($email, $otp);
        
        if (!$isValid) {
            return [
                'success' => false,
                'message' => 'Invalid OTP',
                'data' => null
            ];
        }

        return [
            'success' => true,
            'message' => 'OTP verified successfully',
            'data' => null
        ];
    }

    public function forgotPassword(string $email): array
    {
        $user = $this->authRepository->findByEmail($email);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ];
        }

        $token = $this->authRepository->createPasswordResetToken($email);
        $this->sendVerificationEmailAction->sendPasswordResetEmail($user, $token);
        
        return [
            'success' => true,
            'message' => 'Password reset link sent',
            'data' => null
        ];
    }

    public function resetPassword(PasswordResetData $resetData): array
    {
        $isValidToken = $this->authRepository->validatePasswordResetToken(
            $resetData->email, 
            $resetData->token
        );
        
        if (!$isValidToken) {
            return [
                'success' => false,
                'message' => 'Invalid reset token',
                'data' => null
            ];
        }

        $user = $this->authRepository->findByEmail($resetData->email);
        $hashedPassword = $this->hashPasswordAction->execute($resetData->password);
        
        $this->authRepository->update($user, ['password' => $hashedPassword]);
        $this->authRepository->deletePasswordResetToken($resetData->email);
        
        return [
            'success' => true,
            'message' => 'Password reset successfully',
            'data' => null
        ];
    }

    public function googleAuth(GoogleAuthData $googleData): array
    {
        $user = $this->handleGoogleCallbackAction->execute([
            'name' => $googleData->name,
            'email' => $googleData->email,
            'google_id' => $googleData->google_id,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;
        
        return [
            'success' => true,
            'message' => 'Google authentication successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];
    }
} 