<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 
        'target', 'target_unit', 'frequency', 'is_active'
    ];

    // Habit dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Habit dimiliki oleh satu Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Habit memiliki banyak Schedule (untuk frekuensi custom)
    public function schedules(): HasMany
    {
        return $this->hasMany(HabitSchedule::class);
    }

    // Habit memiliki banyak Log (riwayat check-in)
    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }
}