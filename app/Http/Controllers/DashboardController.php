<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::now()->timezone('Asia/Jakarta')->toDateString();

        // 1. Ambil data habit
        $todayHabits = Habit::where('user_id', $userId)->get();
        
        // 2. Ambil ID habit yang sudah selesai
        $completedHabitIds = HabitLog::whereDate('log_date', $today)
            ->whereHas('habit', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('habit_id')
            ->toArray();

        // 3. Hitung Streak (Logika While loop)
        $currentStreak = 0;
        $checkDate = $today;
        while (true) {
            $hasCompleted = HabitLog::where('log_date', $checkDate)
                ->whereHas('habit', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->exists();
            if ($hasCompleted) {
                $currentStreak++;
                $checkDate = Carbon::parse($checkDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        // 4. Statistik
        $totalHabits = $todayHabits->count();
        $todayHabitsTarget = $totalHabits; // Menentukan target habit
        $todayHabitsCompleted = count($completedHabitIds);
        $completionRate = $totalHabits > 0 ? round(($todayHabitsCompleted / $totalHabits) * 100) : 0;

        // 5. Aktivitas Terbaru
        $recentActivities = HabitLog::with('habit')
            ->whereHas('habit', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest('completed_time')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todayHabits',
            'currentStreak',
            'recentActivities',
            'completionRate',
            'completedHabitIds',
            'totalHabits',
            'todayHabitsCompleted',
            'todayHabitsTarget'
        ));
    }
}