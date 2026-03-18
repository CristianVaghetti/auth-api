<?php

namespace App\Services;

use App\Repository\AuthKeyRepository;
use Carbon\Carbon;

class KeyRotationService
{
    public function __construct(protected AuthKeyRepository $authKeyRepository)
    {
        //
    }

    public function rotate(): string
    {
        $kid = Carbon::now()->format('dmYHis');

        $path = "keys/{$kid}";
        $fullPath = storage_path($path);

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $config = [
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);

        if (!$res) {
            throw new \RuntimeException('Failed to generate key pair');
        }

        openssl_pkey_export($res, $privateKey);

        $publicKeyDetails = openssl_pkey_get_details($res);

        if (!$publicKeyDetails || !isset($publicKeyDetails['key'])) {
            throw new \RuntimeException('Failed to extract public key');
        }

        $publicKey = $publicKeyDetails['key'];

        file_put_contents("{$fullPath}/jwt-private.pem", $privateKey);
        file_put_contents("{$fullPath}/jwt-public.pem", $publicKey);

        $this->authKeyRepository->setAsCurrent($kid, $path);

        return $kid;
    }
}
