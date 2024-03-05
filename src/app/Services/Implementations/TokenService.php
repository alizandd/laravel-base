<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Services\Contracts\TokenInterface;
use Carbon\Carbon;

class TokenService implements TokenInterface
{
    public function createToken(User $user, $type, $rememberMe = false):array
    {
        $user->tokens()->where('name', $type)->delete();
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

    public function createScopeToken(User $user, $type ,$scope):array
    {
        $tokens = $user->tokens;
        foreach ($tokens as $token) {
            if ($token->scopes && in_array($scope, $token->scopes)) {
                $token->delete();
            }
        }

        $tokenResult = $user->createToken($type, [$scope]);
        $expiresAt = Carbon::now()->addDays(1); // Adjust expiration as needed

        $token = $tokenResult->token;
        $token->expires_at = $expiresAt;
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toDateTimeString()
        ];
    }
}
