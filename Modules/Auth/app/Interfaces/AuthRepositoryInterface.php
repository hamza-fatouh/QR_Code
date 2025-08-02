<?php

namespace Modules\Auth\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function findByGoogleId(string $googleId): ?User;
    public function createPasswordResetToken(string $email): string;
    public function validatePasswordResetToken(string $email, string $token): bool;
    public function deletePasswordResetToken(string $email): void;
} 