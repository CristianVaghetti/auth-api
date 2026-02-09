<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('oauth/google')->group(function () {
        Route::get('/', [GoogleAuthController::class, 'redirect']);
        Route::get('/callback', [GoogleAuthController::class, 'callback']);
        Route::post('/refresh', [GoogleAuthController::class, 'refresh']);
    });
});

Route::get('/.well-known/jwks.json', \App\Http\Controllers\Auth\JwksController::class);
