<?php

namespace App\Http\Controllers;

use App\Services\KeyRotationService;
use Illuminate\Http\Request;

class KeysController extends Controller
{
    public function rotate(KeyRotationService $service)
    {
        try {
            $kid = $service->rotate();

            return response()->json([
                'success' => true,
                'message' => 'New keys added succesfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
