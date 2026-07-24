<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header & Navigasi -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Today's Overview</h1>
                <p class="text-slate-500 text-sm mt-0.5">Track your progress and stay consistent.</p>
            </div>

            <!-- Tombol Kelola -->
            <div class="flex gap-3">
                <a href="{{ url('/categories') }}" class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 font-semibold rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-200 shadow-sm flex items-center text-sm">
                    <span class="mr-2">📁</span> Kelola Kategori
                </a>
                <a href="{{ url('/habits') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 border border-indigo-200 font-semibold rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm flex items-center text-sm">
                    <span class="mr-2">🎯</span> Kelola Habit
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-stat-card title="Current Streak" value="{{ $currentStreak }} Days" icon="🔥" color="amber" />
            <x-stat-card title="Today's Habits" value="{{ $todayHabitsCompleted }}/{{ $todayHabitsTarget }}" icon="✅" color="emerald" />
            <x-stat-card title="Completion Rate" value="{{ $completionRate }}%" icon="📈" color="indigo" />
            <x-stat-card title="Total Habits" value="{{ $totalHabits }}" icon="📋" color="slate" />
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI (Today's Habits & Recent Activity) -->
            <div class="lg:col-span-2 space-y-8">
                <x-card>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">Today's Habits</h3>
                                <p class="text-xs text-slate-500">Centang habit yang sudah kamu selesaikan hari ini.</p>
                            </div>
                            <a href="{{ url('/habits') }}" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">+ Add Habit</a>
                        </div>

                        <div class="space-y-3">
                            @forelse($todayHabits as $habit)
                            @php
                            $isCompleted = in_array($habit->id, $completedHabitIds ?? []);
                            $categoryColor = $habit->category ? $habit->category->color : '#6366f1';

                            $freqLabels = [
                            'daily' => ['label' => 'Harian', 'icon' => '🔁', 'bg' => 'bg-blue-50 text-blue-600 border-blue-200'],
                            'weekly' => ['label' => 'Mingguan', 'icon' => '📅', 'bg' => 'bg-purple-50 text-purple-600 border-purple-200'],
                            'weekdays' => ['label' => 'Hari Kerja', 'icon' => '💼', 'bg' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                            'weekend' => ['label' => 'Akhir Pekan', 'icon' => '☕', 'bg' => 'bg-teal-50 text-teal-600 border-teal-200'],
                            'one_time' => ['label' => 'Sekali Selesai', 'icon' => '🎯', 'bg' => 'bg-amber-50 text-amber-600 border-amber-200'],
                            ];
                            $currentFreq = $freqLabels[$habit->frequency] ?? ['label' => ucfirst($habit->frequency), 'icon' => '📌', 'bg' => 'bg-slate-50 text-slate-600 border-slate-200'];
                            @endphp
                            <div class="flex items-center justify-between p-4 pr-5 bg-white border border-slate-200/80 rounded-xl hover:border-slate-300 transition-all shadow-sm">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-2.5 h-10 rounded-full" style="background-color: {{ $categoryColor }}"></div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-slate-800 text-base">{{ $habit->title }}</h4>

                                            <!-- PENANDA FREKUENSI DINAMIS -->
                                            <span class="text-[10px] font-bold px-2 py-0.5 border rounded-md flex items-center gap-1 {{ $currentFreq['bg'] }}">
                                                <span>{{ $currentFreq['icon'] }}</span>
                                                <span>{{ $currentFreq['label'] }}</span>
                                            </span>

                                            @if($habit->category)
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                                                {{ $habit->category->icon }} {{ $habit->category->name }}
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">Target: <span class="font-medium text-slate-700">{{ $habit->target }} {{ $habit->target_unit }}</span></p>
                                    </div>
                                </div>
                                <form action="{{ route('habits.check-in', $habit->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="transition-all shadow-xs px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1 whitespace-nowrap {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        <span>{{ $isCompleted ? '✓ Completed' : '○ Check-in' }}</span>
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <p class="text-sm text-slate-500 mb-2">Belum ada habit yang dibuat.</p>
                                <a href="{{ url('/habits') }}" class="text-xs font-bold text-indigo-600 hover:underline">Buat habit pertamamu di sini &rarr;</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                        <h3 class="font-bold text-slate-900 mb-4">Recent Activity</h3>
                        <div class="space-y-3">
                            @forelse($recentActivities as $log)
                            @php
                            $habitFreq = $log->habit->frequency ?? 'daily';
                            $freqLabels = [
                            'daily' => ['label' => 'Harian', 'bg' => 'bg-blue-50 text-blue-600'],
                            'weekly' => ['label' => 'Mingguan', 'bg' => 'bg-purple-50 text-purple-600'],
                            'weekdays' => ['label' => 'Hari Kerja', 'bg' => 'bg-emerald-50 text-emerald-600'],
                            'weekend' => ['label' => 'Akhir Pekan', 'bg' => 'bg-teal-50 text-teal-600'],
                            'one_time' => ['label' => 'Sekali Selesai', 'bg' => 'bg-amber-50 text-amber-600']
                            ];
                            $currentFreq = $freqLabels[$habitFreq] ?? ['label' => ucfirst($habitFreq), 'bg' => 'bg-slate-50 text-slate-600'];
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-bold">✓</div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-semibold text-slate-800">{{ $log->habit->title ?? 'Habit' }}</p>
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $currentFreq['bg'] }}">{{ $currentFreq['label'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">Completed Today • {{ \Carbon\Carbon::parse($log->completed_time)->format('H:i') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Success</span>
                            </div>
                            @empty
                            <p class="text-sm text-slate-400 text-center py-2">Belum ada aktivitas hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- KOLOM KANAN (Weekly Progress & Achievements) -->
            <div class="space-y-8">
                <x-card>
                    <!-- Section: Weekly Progress -->
                    <div class="p-6 mb-2">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-900">Weekly Progress</h3>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg">This Week</span>
                        </div>

                        <div class="flex flex-col items-center py-2">
                            <div class="relative w-32 h-32">
                                <svg class="w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.8" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-indigo-600" stroke-width="3.8" stroke-dasharray="{{ $completionRate }}, 100" stroke-linecap="round" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <span class="font-extrabold text-slate-900 text-xl">{{ $completionRate }}%</span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Achievements -->
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-900">Achievements</h3>
                            <span class="text-xs text-slate-400 font-medium">Milestones</span>
                        </div>

                        <!-- Grid 2 Kolom yang Lebih Lega -->
                        <div class="grid grid-cols-2 gap-3.5">

                            <!-- Achievement 1: First Habit -->
                            @php $isFirstUnlocked = ($totalCompletionsAllTime ?? 0) >= 1; @endphp
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs transition-all {{ $isFirstUnlocked ? 'hover:border-indigo-300 hover:shadow-sm' : 'opacity-60 bg-slate-50/50' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isFirstUnlocked ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">
                                        🔥
                                    </div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isFirstUnlocked ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $isFirstUnlocked ? 'Unlocked' : 'Locked' }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 tracking-tight">First Habit</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Selesaikan habit pertama</p>
                            </div>

                            <!-- Achievement 2: 7 Day Streak -->
                            @php
                            $isSevenUnlocked = ($currentStreak ?? 0) >= 7;
                            $sevenProgress = ($currentStreak ?? 0) . '/7 Days';
                            @endphp
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs transition-all {{ $isSevenUnlocked ? 'hover:border-indigo-300 hover:shadow-sm' : 'opacity-60 bg-slate-50/50' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isSevenUnlocked ? 'bg-orange-50 text-orange-600' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">
                                        ⚡
                                    </div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isSevenUnlocked ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $isSevenUnlocked ? 'Unlocked' : $sevenProgress }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 tracking-tight">7 Day Streak</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Konsisten seminggu</p>
                            </div>

                            <!-- Achievement 3: Perfect Day -->
                            @php $isPerfectUnlocked = ($todayHabitsTarget > 0 && $todayHabitsCompleted >= $todayHabitsTarget); @endphp
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs transition-all {{ $isPerfectUnlocked ? 'hover:border-indigo-300 hover:shadow-sm' : 'opacity-60 bg-slate-50/50' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isPerfectUnlocked ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">
                                        ⭐
                                    </div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isPerfectUnlocked ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $isPerfectUnlocked ? 'Unlocked' : 'Locked' }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 tracking-tight">Perfect Day</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Selesaikan target hari ini</p>
                            </div>

                            <!-- Achievement 4: 30 Day Streak -->
                            @php
                            $isThirtyUnlocked = ($currentStreak ?? 0) >= 30;
                            $thirtyProgress = ($currentStreak ?? 0) . '/30 Days';
                            @endphp
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs transition-all {{ $isThirtyUnlocked ? 'hover:border-indigo-300 hover:shadow-sm' : 'opacity-60 bg-slate-50/50' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isThirtyUnlocked ? 'bg-purple-50 text-purple-600' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">
                                        {{ $isThirtyUnlocked ? '🏆' : '🔒' }}
                                    </div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isThirtyUnlocked ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $isThirtyUnlocked ? 'Unlocked' : $thirtyProgress }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 tracking-tight">30 Day Streak</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Master konsistensi</p>
                            </div>

                        </div>
                    </div>
                </x-card>
            </div>

        </div>

    </x-container>
</x-layouts.app>