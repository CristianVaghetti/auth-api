<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class JwksController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $publicKey = file_get_contents(config('jwt.public_key'));

        $details = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));

        return response()->json([
            'kty' => 'RSA',
            'use' => 'sig',
            'kid' => config('jwt.key_id', 'auth-api-1'),
            'alg' => 'RS256',
            'n' => $this->base64UrlEncode($details['rsa']['n']),
            'e' => $this->base64UrlEncode($details['rsa']['e']),
        ]);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
