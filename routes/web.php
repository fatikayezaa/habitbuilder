<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

// --- Public Routes ---
Route::get('/', function () {
    return redirect('/login');
});

// --- Dashboard ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- Authenticated Routes ---
Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Habit Management
    Route::get('/habits', [HabitController::class, 'index'])->name('habits.index');
    Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');
    Route::get('/habits/{habit}/edit', [HabitController::class, 'edit'])->name('habits.edit');
    Route::put('/habits/{habit}', [HabitController::class, 'update'])->name('habits.update');
    Route::post('/habits/{habit}/check-in', [HabitController::class, 'checkIn'])->name('habits.check-in');

    // Analytics
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics.index');
});

require __DIR__.'/auth.php';