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

        $bestStreak = $this->getBestStreak($userId);

        // Ambil data kalender konsistensi bulanan untuk widget dashboard
        $calendarData = $this->getMonthlyConsistencyData($userId, $year, $month);

        return view('dashboard', compact(
            'todayHabits',
            'currentStreak',
            'bestStreak',
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

        $currentDate = Carbon::create($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

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

        $bestStreak = $this->getBestStreak($userId);

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

       
        $calendarData = $this->getMonthlyConsistencyData($userId, $year, $month);

        // Hitung statistik ringkasaN
        $completedDaysCount = collect($calendarData)->where('status', 'green')->count();
        $partialDaysCount = collect($calendarData)->where('status', 'yellow')->count();
        $missedDaysCount = collect($calendarData)->where('status', 'red')->count();

        return view('analytics.index', compact(
            'totalHabits',
            'totalCompletedLogs',
            'consistencyScore',
            'weeklyData',
            'currentStreak',
            'bestStreak',
            'mostActiveCategory',
            'smartInsight',
            'calendarData',
            'year',
            'month',
            'prevMonth',
            'nextMonth',
            'completedDaysCount',
            'partialDaysCount',
            'missedDaysCount'
        ));
    }

    private function getMonthlyConsistencyData($userId, $year, $month)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $calendarData = [];
        $habits = Habit::where('user_id', $userId)->get();
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $currentDateObj = Carbon::create($year, $month, $day);
            $currentDateStr = $currentDateObj->toDateString();

            // Hari di masa depan
            if ($currentDateStr > $today) {
                $calendarData[$day] = [
                    'status' => 'white',
                    'label' => 'Future'
                ];
                continue;
            }

            $dayOfWeek = strtolower($currentDateObj->format('l'));

            // Habit yang memang sudah ada pada tanggal tersebut
            $scheduledHabits = $habits->filter(function ($habit) use ($currentDateStr, $currentDateObj, $dayOfWeek) {

                if ($habit->created_at && Carbon::parse($habit->created_at)->toDateString() > $currentDateStr) {
                    return false;
                }

                switch ($habit->frequency) {
                    case 'daily':
                        return true;
                    case 'weekdays':
                        return !in_array($dayOfWeek, ['saturday', 'sunday']);
                    case 'weekend':
                        return in_array($dayOfWeek, ['saturday', 'sunday']);
                    case 'weekly':
                        return $dayOfWeek === 'monday';
                    case 'one_time':
                        $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
                            ->where('status', 'completed')
                            ->where('log_date', '<=', $currentDateStr)
                            ->exists();
                        return !$alreadyCompleted;
                    default:
                        return false;
                }
            });

            $totalScheduled = $scheduledHabits->count();

            if ($totalScheduled == 0) {
                $calendarData[$day] = [
                    'status' => 'white',
                    'label' => 'No Schedule'
                ];
                continue;
            }

            // Ambil log yang benar-benar tersimpan di tanggal ini
            $completedHabitIds = HabitLog::where('log_date', $currentDateStr)
                ->where('status', 'completed')
                ->whereHas('habit', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->pluck('habit_id')
                ->toArray();

            $completedCount = 0;
            foreach ($scheduledHabits as $habit) {
                if (in_array($habit->id, $completedHabitIds)) {
                    $completedCount++;
                }
            }

            if ($completedCount == $totalScheduled) {
                $calendarData[$day] = [
                    'status' => 'green',
                    'label' => 'Completed'
                ];
            } elseif ($completedCount > 0) {
                $calendarData[$day] = [
                    'status' => 'yellow',
                    'label' => 'Partial'
                ];
            } else {
                $calendarData[$day] = [
                    'status' => 'red',
                    'label' => 'Missed'
                ];
            }
        }

        return $calendarData;
    }

    private function getBestStreak($userId)
    {
        $dates = HabitLog::whereHas('habit', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'completed')
            ->select('log_date')
            ->distinct()
            ->orderBy('log_date')
            ->pluck('log_date')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $best = 1;
        $current = 1;

        for ($i = 1; $i < $dates->count(); $i++) {

            $prev = Carbon::parse($dates[$i - 1]);
            $now = Carbon::parse($dates[$i]);

            if ($prev->copy()->addDay()->equalTo($now)) {
                $current++;
            } else {
                $best = max($best, $current);
                $current = 1;
            }
        }

        return max($best, $current);
    }
}
