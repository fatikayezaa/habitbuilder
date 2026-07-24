<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $guarded = [];

    // Relasi User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi Schedules
    public function schedules(): HasMany
    {
        return $this->hasMany(HabitSchedule::class);
    }

    // Relasi Logs
    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }
}