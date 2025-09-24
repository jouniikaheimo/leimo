<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'acted_by_id',
        'user_id',
        'action',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acted_by_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

