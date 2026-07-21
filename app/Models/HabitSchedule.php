<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitSchedule extends Model
{
    protected $fillable = ['habit_id', 'day_of_week'];

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }
}