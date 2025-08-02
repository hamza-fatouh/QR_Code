<?php

namespace Modules\Auth\Actions\User;

use Illuminate\Support\Facades\Hash;

class HashPasswordAction
{
    public function execute(string $password): string
    {
        return Hash::make($password);
    }
} 