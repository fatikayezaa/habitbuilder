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

        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $todayIndoName = $daysMap[Carbon::now()->timezone('Asia/Jakarta')->format('l')];

        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $allUserHabits = Habit::with(['schedules', 'category'])->where('user_id', $userId)->get();

        $todayHabits = $allUserHabits->filter(function ($habit) use ($todayIndoName, $today) {
            $isScheduledToday = $habit->schedules->contains('day_of_week', $todayIndoName);

            if (!$isScheduledToday) {
                return false;
            }

            if ($habit->frequency === 'one_time') {
                $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
                    ->where('status', 'completed')
                    ->whereDate('log_date', '<', $today)
                    ->exists();
                return !$alreadyCompleted;
            }

            return true;
        });

        $completedHabitIds = HabitLog::whereDate('log_date', $today)
            ->whereIn('habit_id', $todayHabits->pluck('id'))
            ->where('status', 'completed')
            ->pluck('habit_id')
            ->toArray();

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

        $todayHabitsTarget = $todayHabits->count();
        $todayHabitsCompleted = count(array_intersect($completedHabitIds, $todayHabits->pluck('id')->toArray()));
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

        $totalHabits = $todayHabitsTarget;

        $bestStreak = $this->getBestStreak($userId);

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

        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $todayIndoName = $daysMap[$today->format('l')];

        $todayScheduledHabits = Habit::with('schedules')->where('user_id', $userId)->get()->filter(function ($habit) use ($todayIndoName, $today) {
            if (!$habit->schedules->contains('day_of_week', $todayIndoName)) {
                return false;
            }
            if ($habit->frequency === 'one_time') {
                $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
                    ->where('status', 'completed')
                    ->whereDate('log_date', '<', $today->toDateString())
                    ->exists();
                return !$alreadyCompleted;
            }
            return true;
        });

        $totalHabits = $todayScheduledHabits->count();

        $totalCompletedLogs = HabitLog::whereHas('habit', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'completed')->count();

        $todayCompleted = HabitLog::whereDate('log_date', $today->toDateString())
            ->whereIn('habit_id', $todayScheduledHabits->pluck('id'))
            ->where('status', 'completed')
            ->count();

        $consistencyScore = $totalHabits > 0 ? round(($todayCompleted / $totalHabits) * 100) : 0;

        $weeklyData = [];
        $completedThisWeek = 0;
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dayIndoNameForLoop = $daysMap[$date->format('l')];

            $dayScheduledHabits = Habit::with('schedules')->where('user_id', $userId)->get()->filter(function ($habit) use ($dayIndoNameForLoop, $date) {
                if (!$habit->schedules->contains('day_of_week', $dayIndoNameForLoop)) {
                    return false;
                }
                if ($habit->frequency === 'one_time') {
                    $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
                        ->where('status', 'completed')
                        ->whereDate('log_date', '<', $date->toDateString())
                        ->exists();
                    return !$alreadyCompleted;
                }
                return true;
            });

            $dayTargetCount = $dayScheduledHabits->count();

            $completedCount = HabitLog::whereDate('log_date', $date->toDateString())
                ->whereIn('habit_id', $dayScheduledHabits->pluck('id'))
                ->where('status', 'completed')
                ->count();

            $completedThisWeek += $completedCount;
            $percentage = $dayTargetCount > 0 ? round(($completedCount / $dayTargetCount) * 100) : 0;

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
        $habits = Habit::with('schedules')->where('user_id', $userId)->get();
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        $daysMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDateObj = Carbon::create($year, $month, $day);
            $currentDateStr = $currentDateObj->toDateString();

            if ($currentDateStr > $today) {
                $calendarData[$day] = [
                    'status' => 'white',
                    'label' => 'Future'
                ];
                continue;
            }

            $dayEnglish = $currentDateObj->format('l');
            $dayIndo = $daysMap[$dayEnglish] ?? '';

            $scheduledHabits = $habits->filter(function ($habit) use ($currentDateStr, $dayIndo) {
                if ($habit->created_at && Carbon::parse($habit->created_at)->toDateString() > $currentDateStr) {
                    return false;
                }

                $isScheduled = $habit->schedules->contains('day_of_week', $dayIndo);
                if (!$isScheduled) {
                    return false;
                }

                if ($habit->frequency === 'one_time') {
                    $alreadyCompleted = HabitLog::where('habit_id', $habit->id)
                        ->where('status', 'completed')
                        ->where('log_date', '<', $currentDateStr)
                        ->exists();
                    return !$alreadyCompleted;
                }

                return true;
            });

            $totalScheduled = $scheduledHabits->count();

            if ($totalScheduled == 0) {
                $calendarData[$day] = [
                    'status' => 'white',
                    'label' => 'No Schedule'
                ];
                continue;
            }

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