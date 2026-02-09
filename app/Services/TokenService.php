<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class TokenService
{
    public function issueTokens(User $user): array
    {
        return [
            'access_token' => $this->makeJwt($user),
            'refresh_token' => $this->makeRefreshToken($user),
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl'),
        ];
    }

    protected function makeRefreshToken(User $user): string
    {
        RefreshToken::revokeForUser($user->id);

        $refreshToken = RefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::uuid(),
            'expires_at' => now()->addDays(30),
        ]);

        return $refreshToken->token;
    }

    protected function makeJwt(User $user): string
    {
        $payload = [
            'iss' => config('jwt.issuer'),
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + config('jwt.ttl'),
        ];

        return JWT::encode(
            $payload,
            file_get_contents(config('jwt.private_key')),
            'RS256',
        );
    }

    public function refreshToken(string $refreshToken): array|false
    {
        $token = RefreshToken::where('token', $refreshToken)->first();

        if (! $token || ! $token->isValid()) {
            return false;
        }

        return $this->issueTokens($token->user);
    }
}
