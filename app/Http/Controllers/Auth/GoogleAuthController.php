<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleAuthService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{
    public function __construct(protected GoogleAuthService $googleAuthService, protected TokenService $tokenService)
    {
        //
    }

    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->googleAuthService->handleGoogleCallback($googleUser);

        return response()->json(
            $this->tokenService->issueTokens($user)
        );
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => ['required', 'string'],
        ]);

        $token = $this->tokenService->refreshToken($request->input('refresh_token'));

        return $token
            ? response()->json($token)
            : response()->json(['message' => 'Invalid refresh token'], 401);
    }
}
