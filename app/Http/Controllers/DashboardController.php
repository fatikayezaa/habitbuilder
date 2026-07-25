<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::now()->timezone('Asia/Jakarta')->toDateString();
        
        // Ambil tahun dan bulan untuk preview kalender di dashboard (default bulan berjalan)
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

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

        // Hitung Current Streak (berdasarkan hari log aktif)
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

        // Ambil data kalender konsistensi bulanan untuk widget dashboard
        $calendarData = $this->getMonthlyConsistencyData($userId, $year, $month);

        return view('dashboard', compact(
            'todayHabits',
            'currentStreak',
            'recentActivities',
            'completionRate',
            'completedHabitIds',
            'totalHabits',
            'todayHabitsCompleted',
            'todayHabitsTarget',
            'totalCompletionsAllTime',
            'calendarData'
        ));
    }

    public function analytics(Request $request)
    {
        $userId = Auth::id();
        $today = Carbon::now()->timezone('Asia/Jakarta');

        $year = $request->input('year', $today->year);
        $month = $request->input('month', $today->month);

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

        // Ambil data kalender bulanan untuk halaman analytics
        $calendarData = $this->getMonthlyConsistencyData($userId, $year, $month);

        return view('analytics.index', compact(
            'totalHabits', 
            'totalCompletedLogs', 
            'consistencyScore', 
            'weeklyData',
            'currentStreak',
            'mostActiveCategory',
            'smartInsight',
            'calendarData',
            'year',
            'month'
        ));
    }

    /**
     * Helper untuk menghitung status konsistensi kalender bulanan (Green, Yellow, Red, White)
     */
    private function getMonthlyConsistencyData($userId, $year, $month)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $calendarData = [];
        $habits = Habit::where('user_id', $userId)->get();
        $todayStr = Carbon::now()->timezone('Asia/Jakarta')->toDateString();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
            
            // Jangan evaluasi hari di masa depan (biarkan putih/default)
            if ($dateString > $todayStr) {
                $calendarData[$day] = ['status' => 'white', 'label' => 'Future'];
                continue;
            }

            $currentDate = Carbon::parse($dateString);
            $dayOfWeek = strtolower($currentDate->format('l'));

            // Tentukan habit yang seharusnya terjadwal di hari itu
            $scheduledHabits = $habits->filter(function ($habit) use ($currentDate, $dayOfWeek) {
                if ($habit->frequency === 'daily') return true;
                if ($habit->frequency === 'weekdays' && !in_array($dayOfWeek, ['saturday', 'sunday'])) return true;
                if ($habit->frequency === 'weekend' && in_array($dayOfWeek, ['saturday', 'sunday'])) return true;
                return false;
            });

            $totalScheduled = $scheduledHabits->count();

            if ($totalScheduled === 0) {
                $calendarData[$day] = ['status' => 'white', 'label' => 'No Schedule'];
                continue;
            }

            // Ambil log yang benar-benar selesai pada tanggal tersebut
            $completedHabitIds = HabitLog::where('user_id', $userId)
                ->whereDate('log_date', $dateString)
                ->where('status', 'completed')
                ->pluck('habit_id')
                ->toArray();

            $completedCount = 0;
            foreach ($scheduledHabits as $habit) {
                if (in_array($habit->id, $completedHabitIds)) {
                    $completedCount++;
                }
            }

            // Tentukan Status Berdasarkan Data Nyata
            if ($completedCount === $totalScheduled) {
                $calendarData[$day] = ['status' => 'green', 'label' => 'Completed']; // 🟢 Hijau
            } elseif ($completedCount > 0) {
                $calendarData[$day] = ['status' => 'yellow', 'label' => 'Partial']; // 🟡 Kuning
            } else {
                $calendarData[$day] = ['status' => 'red', 'label' => 'Missed'];    // 🔴 Merah (atau putih jika ingin bersih)
            }
        }

        return $calendarData;
    }
}