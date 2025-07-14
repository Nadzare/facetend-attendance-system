<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity', 'subjek', 'ip'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
