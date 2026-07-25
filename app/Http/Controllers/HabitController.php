<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Category;
use App\Models\HabitLog;
use App\Models\HabitSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::with(['category', 'schedules'])->where('user_id', Auth::id())->get();
        $categories = Category::where('user_id', Auth::id())->get();

        return view('habits.index', compact('habits', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'frequency'   => 'required|in:daily,weekdays,weekend,weekly,one_time',
            'target'      => 'required|string|max:100',
            'target_unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'days'        => 'nullable|array',
        ]);

        $habit = Habit::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'target'      => $request->target,
            'target_unit' => $request->target_unit,
            'frequency'   => $request->frequency,
            'is_active'   => true,
        ]);

        $this->saveSchedules($habit, $request);

        return redirect()->back();
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id === Auth::id()) {
            $habit->schedules()->delete(); 
            $habit->delete();
        }
        return redirect()->back();
    }

    public function edit(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::where('user_id', Auth::id())->get();

        return view('habits.edit', compact('habit', 'categories'));
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'frequency'   => 'required|in:daily,weekdays,weekend,weekly,one_time',
            'target'      => 'required|string|max:100',
            'target_unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'days'        => 'nullable|array',
        ]);

        $habit->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'target'      => $request->target,
            'target_unit' => $request->target_unit,
            'frequency'   => $request->frequency,
        ]);

        $habit->schedules()->delete();
        $this->saveSchedules($habit, $request);

        return redirect('/habits');
    }

    private function saveSchedules(Habit $habit, Request $request)
    {
        $days = [];

        // Mapping hari Inggris ke Indonesia
        $indoDays = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        if ($request->has('days') && is_array($request->days) && count($request->days) > 0) {
            foreach ($request->days as $d) {
                $days[] = $indoDays[$d] ?? $d;
            }
        } else {
            $rawDays = [];
            switch ($habit->frequency) {
                case 'daily':
                    $rawDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    break;
                case 'weekdays':
                    $rawDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    break;
                case 'weekend':
                    $rawDays = ['Saturday', 'Sunday'];
                    break;
                case 'weekly':
                    $rawDays = ['Sunday']; 
                    break;
                case 'one_time':
                    $rawDays = [Carbon::now()->format('l')]; 
                    break;
            }

            foreach ($rawDays as $d) {
                $days[] = $indoDays[$d] ?? $d;
            }
        }

        foreach ($days as $day) {
            HabitSchedule::create([
                'habit_id'    => $habit->id,
                'day_of_week' => $day,
            ]);
        }
    }

    public function checkIn(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $today = Carbon::now()->timezone('Asia/Jakarta')->toDateString();

        $existingLog = HabitLog::where('habit_id', $habit->id)
            ->where('log_date', $today)
            ->first();

        if ($existingLog) {
            $existingLog->delete();
            return back()->with('success', 'Check-in dibatalkan.');
        }

        HabitLog::create([
            'habit_id'       => $habit->id,
            'log_date'       => $today,
            'status'         => 'completed',
            'completed_time' => Carbon::now()->timezone('Asia/Jakarta')->toTimeString(),
            'notes'          => 'Selesai dari Dashboard',
            'mood'           => 5,
        ]);

        return back()->with('success', 'Mantap! Habit berhasil diselesaikan hari ini. 🎉');
    }
}