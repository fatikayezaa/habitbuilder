<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header & Navigasi -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Today's Overview</h1>
                <p class="text-slate-500 dark:text-slate-400">Track your progress and stay consistent.</p>
            </div>

            <!-- Pindah Halaman -->
            <div class="flex gap-3">
                <a href="{{ url('/categories') }}" class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition duration-200 shadow-sm flex items-center text-sm">
                    <span class="mr-2">📁</span> Kelola Kategori
                </a>
                <a href="{{ url('/habits') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 border border-indigo-200 font-semibold rounded-lg hover:bg-indigo-600 hover:text-white transition duration-200 shadow-sm flex items-center text-sm">
                    <span class="mr-2">🎯</span> Kelola Habit
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <x-stat-card title="Current Streak" value="{{ $currentStreak }} Days" icon="🔥" color="amber" />
            <x-stat-card title="Today's Habits" value="{{ $todayHabitsCompleted }}/{{ $todayHabitsTarget }}" icon="✅" color="emerald" />
            <x-stat-card title="Completion Rate" value="{{ $completionRate }}%" icon="📈" color="indigo" />
            <x-stat-card title="Total Habits" value="{{ $totalHabits }}" icon="📋" color="slate" />
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-2 space-y-8">
                <x-card>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-semibold text-slate-900">Today's Habits</h3>
                            <a href="{{ url('/habits') }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-800">+ Add Habit</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($todayHabits as $habit)
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-8 rounded bg-indigo-500"></div>
                                    <div>
                                        <h4 class="font-semibold text-slate-800">{{ $habit->title }}</h4>
                                        <p class="text-xs text-slate-500">{{ $habit->target }} {{ $habit->target_unit }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('habits.check-in', $habit->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-colors {{ in_array($habit->id, $completedHabitIds ?? []) ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300' }}">✓</button>
                                </form>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 text-center py-4">Belum ada habit hari ini.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-6 border-t border-slate-100">
                        <h3 class="font-semibold text-slate-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            @forelse($recentActivities as $log)
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-3 last:border-0">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs">✓</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $log->habit->title }}</p>
                                    <p class="text-xs text-slate-500">Selesai {{ \Carbon\Carbon::parse($log->completed_time)->format('H:i') }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-slate-500 text-center">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- KOLOM KANAN -->
            <div class="space-y-8">
                <x-card>
                    <!-- Section: Weekly Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-semibold text-slate-900">Weekly Progress</h3>
                            <span class="text-xs font-medium px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md">This Week</span>
                        </div>

                        @if($recentActivities->isEmpty())
                        <div class="h-40 flex flex-col items-center justify-center text-center p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                            <span class="text-3xl mb-2">🌱</span>
                            <p class="text-sm text-slate-500 font-medium">Belum ada progres</p>
                        </div>
                        @else
                        <div class="flex flex-col items-center py-2">
                            <div class="relative w-28 h-28">
                                <svg class="w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="4" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-indigo-500" stroke-width="4" stroke-dasharray="{{ $completionRate }}, 100" stroke-linecap="round" fill="none" stroke="currentColor" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center font-bold text-indigo-600 text-lg">
                                    {{ $completionRate }}%
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Section: Achievements -->
                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="font-semibold text-slate-900 mb-4">Achievements</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <!-- Achievement 1 -->
                            <div class="group flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition">
                                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-xl">🔥</div>
                                <span class="text-[10px] font-bold text-slate-700 uppercase">Streak</span>
                            </div>
                            <!-- Achievement 2 -->
                            <div class="group flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-xl">🏆</div>
                                <span class="text-[10px] font-bold text-slate-700 uppercase">Pro</span>
                            </div>
                            <!-- Achievement 3 -->
                            <div class="group flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-xl grayscale">🔒</div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Locked</span>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        </div>
    </x-container>
</x-layouts.app>