<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'is_completed',
        'child_id',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(User::class, 'child_id');
    }
}
