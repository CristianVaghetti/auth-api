<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repository\AuthKeyRepository;
use Illuminate\Http\JsonResponse;

class JwksController extends Controller
{
    public function __invoke(AuthKeyRepository $authKeyRepository): JsonResponse
    {
        $keys = $authKeyRepository
            ->getActive()
            ->map(function ($key) {

                $publicKey = file_get_contents(
                    storage_path("{$key->path}/jwt-public.pem")
                );

                $details = openssl_pkey_get_details(
                    openssl_pkey_get_public($publicKey)
                );

                return [
                    'kty' => 'RSA',
                    'use' => 'sig',
                    'kid' => $key->kid,
                    'alg' => 'RS256',
                    'n' => $this->base64UrlEncode($details['rsa']['n']),
                    'e' => $this->base64UrlEncode($details['rsa']['e']),
                ];
            });

        return response()->json([
            'keys' => $keys->values()
        ]);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
