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
                <a href="{{ url('/categories') }}" class="px-4 py-2 bg-sage-100 text-emeraldAction border border-sage-200 font-semibold rounded-xl hover:bg-emeraldAction hover:text-white transition-all duration-200 shadow-xs hover:-translate-y-0.5 flex items-center text-sm">
                    <span class="mr-2">📁</span> Kelola Kategori
                </a>
                <a href="{{ url('/habits') }}" class="px-4 py-2 bg-emerald-50 text-emeraldAction border border-emerald-200 font-semibold rounded-xl hover:bg-emeraldAction hover:text-white transition-all duration-200 shadow-xs hover:-translate-y-0.5 flex items-center text-sm">
                    <span class="mr-2">🎯</span> Kelola Habit
                </a>
            </div>
        </div>

        <!-- Stat Cards dengan Efek Hover Premium -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <x-stat-card title="Current Streak" value="{{ $currentStreak }} Days" icon="🔥" color="amber" />
            </div>
            <div class="transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <x-stat-card title="Today's Habits" value="{{ $todayHabitsCompleted }}/{{ $todayHabitsTarget }}" icon="✅" color="emerald" />
            </div>
            <div class="transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <x-stat-card title="Completion Rate" value="{{ $completionRate }}%" icon="📈" color="emerald" />
            </div>
            <div class="transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <x-stat-card title="Total Habits" value="{{ $totalHabits }}" icon="📋" color="slate" />
            </div>
        </div>

        <!-- Main Content Area: Layout Seimbang Kiri & Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI (Today's Habits, Recent Activity, & Achievements) -> Span 2 -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Today's Habits Card -->
                <x-card class="transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">Today's Habits</h3>
                                <p class="text-xs text-slate-500">Centang habit yang sudah kamu selesaikan hari ini.</p>
                            </div>
                            <a href="{{ url('/habits') }}" class="text-sm text-emeraldAction font-semibold hover:underline transition-colors">+ Add Habit</a>
                        </div>

                        <div class="space-y-3">
                            @forelse($todayHabits as $habit)
                            @php
                            $isCompleted = in_array($habit->id, $completedHabitIds ?? []);
                            $categoryColor = $habit->category ? $habit->category->color : '#157F5C';

                            $freqLabels = [
                            'daily' => ['label' => 'Harian', 'icon' => '🔁', 'bg' => 'bg-sage-100 text-emeraldAction border-sage-200'],
                            'weekly' => ['label' => 'Mingguan', 'icon' => '📅', 'bg' => 'bg-purple-50 text-purple-600 border-purple-200'],
                            'weekdays' => ['label' => 'Hari Kerja', 'icon' => '💼', 'bg' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                            'weekend' => ['label' => 'Akhir Pekan', 'icon' => '☕', 'bg' => 'bg-teal-50 text-teal-600 border-teal-200'],
                            'one_time' => ['label' => 'Sekali Selesai', 'icon' => '🎯', 'bg' => 'bg-amber-50 text-amber-600 border-amber-200'],
                            ];
                            $currentFreq = $freqLabels[$habit->frequency] ?? ['label' => ucfirst($habit->frequency), 'icon' => '📌', 'bg' => 'bg-slate-50 text-slate-600 border-slate-200'];
                            @endphp
                            <div class="flex items-center justify-between p-4 pr-5 bg-white border border-slate-200/80 rounded-xl hover:border-emerald-300 transition-all shadow-xs">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-2.5 h-10 rounded-full" style="background-color: {{ $categoryColor }}"></div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-slate-800 text-base">{{ $habit->title }}</h4>
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
                                    <button type="submit" class="transition-all shadow-xs px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1 whitespace-nowrap {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100' : 'bg-emeraldAction text-white hover:bg-[#0F6E52]' }}">
                                        <span>{{ $isCompleted ? '✓ Completed' : '○ Check-in' }}</span>
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <p class="text-sm text-slate-500 mb-2">Belum ada habit yang dibuat.</p>
                                <a href="{{ url('/habits') }}" class="text-xs font-bold text-emeraldAction hover:underline">Buat habit pertamamu di sini &rarr;</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </x-card>

                <!-- Recent Activity Card (Dengan Max-Height 350px & Scroll Halus) -->
                <x-card class="transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-900">Recent Activity</h3>
                            <span class="text-xs text-slate-400">Live logs</span>
                        </div>
                        <div class="space-y-3 max-h-[350px] overflow-y-auto pr-1">
                            @forelse($recentActivities as $log)
                            @php
                            $habitFreq = $log->habit->frequency ?? 'daily';
                            $freqLabels = [
                            'daily' => ['label' => 'Harian', 'bg' => 'bg-sage-100 text-emeraldAction'],
                            'weekly' => ['label' => 'Mingguan', 'bg' => 'bg-purple-50 text-purple-600'],
                            'weekdays' => ['label' => 'Hari Kerja', 'bg' => 'bg-emerald-50 text-emerald-600'],
                            'weekend' => ['label' => 'Akhir Pekan', 'bg' => 'bg-teal-50 text-teal-600'],
                            'one_time' => ['label' => 'Sekali Selesai', 'bg' => 'bg-amber-50 text-amber-600']
                            ];
                            $currentFreq = $freqLabels[$habitFreq] ?? ['label' => ucfirst($habitFreq), 'bg' => 'bg-slate-50 text-slate-600'];
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-slate-50/70 border border-slate-100 rounded-xl shadow-xs">
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
                            <p class="text-sm text-slate-400 text-center py-4">Belum ada aktivitas hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                </x-card>

                <!-- Achievements Grid 2x2 dengan Warna Gamifikasi Hidup -->
                <x-card class="transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-900">Achievements</h3>
                            <span class="text-xs text-slate-400 font-medium">Milestones</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3.5">
                            <!-- Achievement 1: First Habit -->
                            @php $isFirstUnlocked = ($totalCompletionsAllTime ?? 0) >= 1; @endphp
                            <div class="flex flex-col p-4 rounded-2xl border shadow-xs transition-all hover:-translate-y-0.5 {{ $isFirstUnlocked ? 'bg-[#FFF8E5] border-amber-200' : 'bg-slate-50/50 border-slate-200/80 opacity-60' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isFirstUnlocked ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">🔥</div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isFirstUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $isFirstUnlocked ? 'Unlocked' : 'Locked' }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800">First Habit</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Selesaikan habit pertama</p>
                            </div>

                            <!-- Achievement 2: 7 Day Streak -->
                            @php
                            $isSevenUnlocked = ($currentStreak ?? 0) >= 7;
                            $sevenProgress = ($currentStreak ?? 0) . '/7 Days';
                            @endphp
                            <div class="flex flex-col p-4 rounded-2xl border shadow-xs transition-all hover:-translate-y-0.5 {{ $isSevenUnlocked ? 'bg-[#ECFFF6] border-emerald-200' : 'bg-slate-50/50 border-slate-200/80 opacity-60' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isSevenUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">⚡</div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isSevenUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $isSevenUnlocked ? 'Unlocked' : $sevenProgress }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800">7 Day Streak</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Konsisten seminggu</p>
                            </div>

                            <!-- Achievement 3: Perfect Day -->
                            @php $isPerfectUnlocked = ($todayHabitsTarget > 0 && $todayHabitsCompleted >= $todayHabitsTarget); @endphp
                            <div class="flex flex-col p-4 rounded-2xl border shadow-xs transition-all hover:-translate-y-0.5 {{ $isPerfectUnlocked ? 'bg-[#ECFFF6] border-emerald-200' : 'bg-slate-50/50 border-slate-200/80 opacity-60' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isPerfectUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">⭐</div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isPerfectUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $isPerfectUnlocked ? 'Unlocked' : 'Locked' }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800">Perfect Day</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Selesaikan target hari ini</p>
                            </div>

                            <!-- Achievement 4: 30 Day Streak -->
                            @php
                            $isThirtyUnlocked = ($currentStreak ?? 0) >= 30;
                            $thirtyProgress = ($currentStreak ?? 0) . '/30 Days';
                            @endphp
                            <div class="flex flex-col p-4 rounded-2xl border shadow-xs transition-all hover:-translate-y-0.5 {{ $isThirtyUnlocked ? 'bg-purple-50/70 border-purple-200' : 'bg-slate-50/50 border-slate-200/80 opacity-60' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $isThirtyUnlocked ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-400 grayscale' }} flex items-center justify-center text-lg">{{ $isThirtyUnlocked ? '🏆' : '🔒' }}</div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isThirtyUnlocked ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $isThirtyUnlocked ? 'Unlocked' : $thirtyProgress }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800">30 Day Streak</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">Master konsistensi</p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- KOLOM KANAN (Weekly Progress & Expanded Monthly Consistency Calendar) -> Span 1 -->
            <div class="space-y-8">

                <!-- Weekly Progress Ring Card -->
                <x-card class="transition-all duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-900">Weekly Progress</h3>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg">This Week</span>
                        </div>

                        <div class="flex flex-col items-center py-2">
                            <div class="relative w-36 h-36">
                                <svg class="w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.8" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-emeraldAction transition-all duration-1000 ease-out" stroke-width="3.8" stroke-dasharray="{{ $completionRate }}, 100" stroke-linecap="round" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <span class="font-extrabold text-slate-900 text-2xl">{{ $completionRate }}%</span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Expanded Monthly Consistency Calendar & Streak Summary (Menyeimbangkan Tinggi Kolom Kiri) -->
                <x-card class="bg-gradient-to-br from-sage-100/40 to-emerald-50/30 border-emerald-200/60 transition-all duration-300 hover:shadow-md">
                    <div class="p-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-200/60 flex items-center justify-center text-emerald-800 text-lg">📅</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Monthly Consistency</h4>
                                    <p class="text-xs text-slate-500">July 2026 Overview</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-200/50 px-2 py-1 rounded-lg">🔥 Active</span>
                        </div>

                        <!-- Grid Kalender Bulanan Penuh (Dinamis dari Controller) -->
                        <div class="space-y-1.5">
                            <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 pb-1">
                                <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                            </div>
                            <div class="grid grid-cols-7 gap-1.5">
                                @foreach ($calendarData as $day => $data)
                                @php
                                $bgClass = match($data['status']) {
                                'green' => 'bg-emeraldAction text-white font-bold',
                                'yellow' => 'bg-amber-300 text-slate-900 font-bold',
                                'red' => 'bg-rose-500 text-white font-bold',
                                default => 'bg-white text-slate-400 border border-slate-200'
                                };

                                // Tandai hari ini (25 Juli 2026) dengan ring khusus
                                $isToday = ($day == now()->day);
                                $todayRing = $isToday ? 'ring-2 ring-emerald-700 ring-offset-1' : '';
                                @endphp

                                <div class="h-7 rounded-md flex items-center justify-center text-[11px] {{ $bgClass }} {{ $todayRing }}" title="Day {{ $day }}: {{ $data['label'] }}">
                                    {{ $day }}
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Legenda Status -->
                        <div class="flex items-center justify-between text-[11px] text-slate-600 px-1 pt-1 border-t border-emerald-200/40">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emeraldAction"></span> Completed</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-300"></span> Partial</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Missed</span>
                        </div>

                        <!-- Ringkasan Streak Singkat -->
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="p-3 bg-white/80 rounded-xl border border-emerald-100 shadow-2xs">
                                <span class="text-[10px] text-slate-400 font-medium uppercase">Current Streak</span>
                                <p class="text-sm font-extrabold text-slate-900 mt-0.5">🔥 {{ $currentStreak ?? 2 }} Days</p>
                            </div>
                            <div class="p-3 bg-white/80 rounded-xl border border-emerald-100 shadow-2xs">
                                <span class="text-[10px] text-slate-400 font-medium uppercase">Best Streak</span>
                                <p class="text-sm font-extrabold text-emeraldAction mt-0.5">🏆 16 Days</p>
                            </div>
                        </div>

                        <a href="{{ route('analytics.index') }}" class="w-full block text-center py-2.5 bg-emeraldAction text-white text-xs font-bold rounded-xl hover:bg-[#0F6E52] transition-colors shadow-xs">
                            Buka Analytics Lengkap &rarr;
                        </a>
                    </div>
                </x-card>

            </div>

        </div>

    </x-container>
</x-layouts.app>