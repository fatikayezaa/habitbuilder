<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Category;
use App\Models\HabitLog; 
use Carbon\Carbon;       
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Habit::with('category')->where('user_id', Auth::id())->get();
        $categories = Category::where('user_id', Auth::id())->get();
        
        return view('habits.index', compact('habits', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'frequency' => 'required|in:daily,weekdays,weekend,weekly,custom',
            'target' => 'required|string|max:100',
            'target_unit' => 'required|string|max:50',
        ]);

        Habit::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'target' => $request->target,
            'target_unit' => $request->target_unit,
            'frequency' => $request->frequency,
            'is_active' => true,
        ]);

        return redirect()->back();
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id === Auth::id()) {
            $habit->delete();
        }
        return redirect()->back();
    }

    // === EDIT ===

    public function edit(Habit $habit)
    {
        // Pastikan hanya pemilik yang bisa melihat halaman edit
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }
        
        // ambil data kategori
        $categories = Category::where('user_id', Auth::id())->get();
        
        return view('habits.edit', compact('habit', 'categories'));
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'frequency' => 'required|in:daily,weekdays,weekend,weekly,custom',
            'target' => 'required|string|max:100',
            'target_unit' => 'required|string|max:50',
        ]);

        $habit->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'target' => $request->target,
            'target_unit' => $request->target_unit,
            'frequency' => $request->frequency,
        ]);

        return redirect('/habits');
    }

    // === CHECK-IN LOGIC ===
    public function checkIn(Request $request, Habit $habit)
    {
        // 🔒 KEAMANAN: Pastikan hanya pemilik habit yang bisa melakukan check-in
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. Ambil tanggal hari ini (Waktu Jakarta)
        $today = Carbon::now()->timezone('Asia/Jakarta')->toDateString();

        // 2. Cek apakah habit ini sudah di-check-in hari ini
        $existingLog = HabitLog::where('habit_id', $habit->id)
            ->where('log_date', $today)
            ->first();

        // 3. Jika sudah ada, kita hapus (Toggle uncheck)
        if ($existingLog) {
            $existingLog->delete();
            return back()->with('success', 'Check-in dibatalkan.');
        }

        // 4. Jika belum ada, kita buat log baru dengan status completed
        HabitLog::create([
            'habit_id' => $habit->id,
            'log_date' => $today,
            'status' => 'completed',
            'completed_time' => Carbon::now()->timezone('Asia/Jakarta')->toTimeString(),
            'notes' => 'Selesai dari Dashboard',
            'mood' => 5, 
        ]);

        return back()->with('success', 'Mantap! Habit berhasil diselesaikan hari ini. 🎉');
    }
}