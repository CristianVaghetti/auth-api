<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthKey extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'kid';
    protected $keyType = 'string';

    protected $fillable = [
        'kid',
        'path',
        'is_current',
        'is_active',
        'retired_at',
    ];
}
