<?php

namespace App\Services;

use App\Models\AuthKey;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repository\AuthKeyRepository;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TokenService
{
    public function __construct(protected AuthKeyRepository $authKeyRepository)
    {
        //
    }

    public function issueTokens(User $user): array
    {
        return [
            'access_token' => $this->makeJwt($user),
            'refresh_token' => $this->makeRefreshToken($user),
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl'),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
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
            'aud' => config('jwt.allowed_clients'),
            'iat' => time(),
            'exp' => time() + config('jwt.ttl'),
        ];

        $authKey = $this->authKeyRepository->getCurrent();

        return JWT::encode(
            $payload,
            Storage::disk('storage')->get($authKey->path . '/jwt-private.pem'),
            'RS256',
            $authKey->kid 
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
