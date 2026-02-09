<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;

class GoogleAuthService
{
    public function __construct()
    {
        //
    }

    public function handleGoogleCallback($googleUser)
    {
        return User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'avatar' => $googleUser->avatar,
            ]
        );
    }
}
