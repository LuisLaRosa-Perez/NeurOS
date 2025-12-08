<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OllamaRequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model_name',
        'prompt',
        'response',
        'status',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
