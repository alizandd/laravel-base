<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\Contracts\TokenInterface;
use Carbon\Carbon;

class TokenService implements TokenInterface
{
    public function createToken(User $user, $type, $rememberMe = false):array
    {
        $tokenResult = $user->createToken($type);
        $token = $tokenResult->token;
        if ($rememberMe) {
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
        }
        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ];
    }
}
