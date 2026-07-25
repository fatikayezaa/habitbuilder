<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme',
        'timezone',
        'language',
        'reminder_notification',
        'email_notification',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}