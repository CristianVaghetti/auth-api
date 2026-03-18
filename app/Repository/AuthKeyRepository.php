<?php
namespace App\Repository;

use App\Models\AuthKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthKeyRepository
{
    public function setAsCurrent(string $kid, string $path): void
    {
        DB::transaction(function () use ($kid, $path) {
            AuthKey::where('is_current', true)
                ->update(['is_current' => false]);

            AuthKey::create([
                'kid' => $kid,
                'path' => $path,
                'is_current' => true,
                'is_active' => true,
            ]);
        });
    }
    
    public function getCurrent(): AuthKey
    {
        return AuthKey::where('is_current', true)->sole();
    }

    public function getActive()
    {
        return AuthKey::where('is_active', true)->get();
    }

    public function retireOld(int $days = 7): void
    {
        AuthKey::where('is_current', false)
            ->where('is_active', true)
            ->where('created_at', '<', now()->subDays($days))
            ->update([
                'is_active' => false,
                'retired_at' => Carbon::now()
            ]);
    }
}