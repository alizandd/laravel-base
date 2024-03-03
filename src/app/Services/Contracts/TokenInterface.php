<?php

namespace App\Services\Contracts;

use App\Models\User;

interface TokenInterface
{
    public function createToken(User $user, string $type , $rememberMe = false): array;
}
