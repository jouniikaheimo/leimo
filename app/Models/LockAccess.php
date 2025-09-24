<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LockAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_number',
        'lock_number',
        'location',
        'opened_at',
        'result',
        'meta',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

