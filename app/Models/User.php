<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    // Relasi ke Category
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    // Relasi ke Habit
    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class);
    }

    // Relasi ke Setting
    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    // Relasi Many-to-Many ke Achievement melalui user_achievements
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
