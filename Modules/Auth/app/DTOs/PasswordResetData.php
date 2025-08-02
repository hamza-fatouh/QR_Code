<?php

namespace Modules\Auth\DTOs;

class PasswordResetData
{
    public function __construct(
        public readonly string $email,
        public readonly string $token,
        public readonly string $password
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email: $data['email'],
            token: $data['token'],
            password: $data['password']
        );
    }
} 