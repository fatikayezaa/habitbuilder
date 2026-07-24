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

        $allUserHabits = Habit::where('user_id', $userId)->get();

        $completedHabitIds = HabitLog::whereDate('log_date', $today)
            ->whereHas('habit', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('habit_id')
            ->toArray();

        $todayHabits = $allUserHabits->filter(function ($habit) use ($today) {
            if ($habit->frequency !== 'one_time') {
                return true;
            }

            return !HabitLog::where('habit_id', $habit->id)
                ->where('status', 'completed')
                ->whereDate('log_date', '<=', $today)
                ->exists();
        });

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

        $allPossibleTodayHabits = $allUserHabits->filter(function ($habit) use ($today) {
            if ($habit->frequency !== 'one_time') {
                return true;
            }

            return !HabitLog::where('habit_id', $habit->id)
                ->where('status', 'completed')
                ->whereDate('log_date', '<', $today)
                ->exists();
        });

        $todayHabitsTarget = $allPossibleTodayHabits->count();
        $todayHabitsCompleted = count(array_intersect($completedHabitIds, $allPossibleTodayHabits->pluck('id')->toArray()));
        $completionRate = $todayHabitsTarget > 0 ? min(round(($todayHabitsCompleted / $todayHabitsTarget) * 100), 100) : 0;

        $recentActivities = HabitLog::with('habit')
            ->whereHas('habit', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest('completed_time')
            ->limit(5)
            ->get();

        $totalCompletionsAllTime = HabitLog::whereHas('habit', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'completed')->count();

        $totalHabits = $allUserHabits->count();

        return view('dashboard', compact(
            'todayHabits',
            'currentStreak',
            'recentActivities',
            'completionRate',
            'completedHabitIds',
            'totalHabits',
            'todayHabitsCompleted',
            'todayHabitsTarget',
            'totalCompletionsAllTime'
        ));
    }

    public function analytics()
    {
        $userId = Auth::id();
        $today = Carbon::now()->timezone('Asia/Jakarta');

        $totalHabits = Habit::where('user_id', $userId)->count();

        $totalCompletedLogs = HabitLog::whereHas('habit', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'completed')->count();

        $todayCompleted = HabitLog::whereDate('log_date', $today->toDateString())
            ->whereHas('habit', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
            
        $consistencyScore = $totalHabits > 0 ? round(($todayCompleted / $totalHabits) * 100) : 0;

        $weeklyData = [];
        $completedThisWeek = 0; 
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            
            $completedCount = HabitLog::whereDate('log_date', $date->toDateString())
                ->whereHas('habit', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count();

            $completedThisWeek += $completedCount;
            $percentage = $totalHabits > 0 ? round(($completedCount / $totalHabits) * 100) : 0;

            $weeklyData[] = [
                'day' => $date->translatedFormat('D'),
                'percentage' => $percentage,
                'is_today' => $i === 0
            ];
        }

        $currentStreak = 0;
        $checkDate = $today->toDateString();
        while (true) {
            $hasCompleted = HabitLog::where('log_date', $checkDate)
                ->whereHas('habit', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->exists();
            if ($hasCompleted) {
                $currentStreak++;
                $checkDate = Carbon::parse($checkDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        $mostActiveHabit = Habit::where('user_id', $userId)
            ->withCount(['logs' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderByDesc('logs_count')
            ->first();

        $mostActiveCategory = ($mostActiveHabit && $mostActiveHabit->category) 
            ? $mostActiveHabit->category->name 
            : 'Belum Ada';

        $smartInsight = "Mulai centang habit pertamamu untuk melihat analisis performa di sini.";
        if ($totalCompletedLogs > 0) {
            if ($consistencyScore == 100) {
                $smartInsight = "Luar biasa! Kamu menyelesaikan semua target hari ini. Pertahankan momentum sempurna ini!";
            } elseif ($currentStreak >= 3) {
                $smartInsight = "Kamu sedang dalam streak {$currentStreak} hari beruntun! Jangan biarkan apinya padam besok.";
            } elseif ($completedThisWeek > 0) {
                $smartInsight = "Kamu berhasil check-in {$completedThisWeek} kali dalam 7 hari terakhir. Yuk, tingkatkan lagi konsistensinya!";
            }
        }

        return view('analytics.index', compact(
            'totalHabits', 
            'totalCompletedLogs', 
            'consistencyScore', 
            'weeklyData',
            'currentStreak',
            'mostActiveCategory',
            'smartInsight'
        ));
    }
}