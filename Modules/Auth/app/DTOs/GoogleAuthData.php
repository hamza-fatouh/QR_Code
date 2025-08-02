<?php

namespace Modules\Auth\DTOs;

class GoogleAuthData
{
    public function __construct(
        public readonly string $token,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $google_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            token: $data['token'],
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            google_id: $data['google_id'] ?? null
        );
    }
} 