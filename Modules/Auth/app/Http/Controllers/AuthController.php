<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\Auth\DTOs\GoogleAuthData;
use Modules\Auth\DTOs\LoginData;
use Modules\Auth\DTOs\PasswordResetData;
use Modules\Auth\DTOs\ProfileUpdateData;
use Modules\Auth\DTOs\RegisterData;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\UpdateProfileRequest;
use Modules\Auth\Http\Resources\UserResource;
use Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService)
    {
        //
    }

    public function login(LoginRequest $request)
    {
        $loginData = LoginData::fromRequest($request->validated());
        $result = $this->authService->login($loginData);

        if (!$result['success']) {
            return $this->error($result['message'], 401);
        }

        return $this->success([
            'user' => new UserResource($result['data']['user']),
            'token' => $result['data']['token']
        ], $result['message']);
    }

    public function register(RegisterRequest $request)
    {
        $registerData = RegisterData::fromRequest($request->validated());
        $result = $this->authService->register($registerData);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success([
            'user' => new UserResource($result['data']['user']),
            'token' => $result['data']['token']
        ], $result['message']);
    }

    public function logout()
    {
        $result = $this->authService->logout();
        return $this->success(null, $result['message']);
    }

    public function me()
    {
        $result = $this->authService->getProfile();
        return $this->success(new UserResource($result['data']), $result['message']);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profileData = ProfileUpdateData::fromRequest($request->validated());
        $result = $this->authService->updateProfile($profileData);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(new UserResource($result['data']), $result['message']);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $result = $this->authService->sendOtp($request->email);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success(null, $result['message']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $result = $this->authService->verifyOtp($request->email, $request->otp);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(null, $result['message']);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $result = $this->authService->forgotPassword($request->email);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success(null, $result['message']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetData = PasswordResetData::fromRequest($request->validated());
        $result = $this->authService->resetPassword($resetData);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(null, $result['message']);
    }

    public function googleAuth(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'google_id' => 'sometimes|string'
        ]);

        $googleData = GoogleAuthData::fromRequest($request->all());
        $result = $this->authService->googleAuth($googleData);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success([
            'user' => new UserResource($result['data']['user']),
            'token' => $result['data']['token']
        ], $result['message']);
    }
}
