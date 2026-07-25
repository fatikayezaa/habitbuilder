<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header -->
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-8 h-8 flex items-center justify-center bg-white text-slate-700 rounded-xl hover:bg-slate-100 transition-colors shadow-xs border border-slate-200" title="Dashboard">
                    <span class="font-bold">&larr;</span>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Performance Analytics</h1>
            </div>
            <p class="text-slate-500 text-sm ml-11">Analisis mendalam mengenai konsistensi dan produktivitas kebiasaanmu.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <x-stat-card title="Total Check-Ins" value="{{ $totalCompletedLogs ?? 0 }} Times" icon="📊" color="emerald" />
            <x-stat-card title="Active Habits" value="{{ $totalHabits ?? 0 }} Habits" icon="🎯" color="indigo" />
            <x-stat-card title="Consistency Score" value="{{ $consistencyScore ?? 0 }}%" icon="⭐" color="amber" />
        </div>

        <!-- Main Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Grafik -->
            <div class="lg:col-span-2">
                <x-card class="h-full">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">Weekly Consistency Trend</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Grafik penyelesaian habit dalam 7 hari terakhir.</p>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 bg-slate-50 text-slate-600 border border-slate-200 rounded-xl">Last 7 Days</span>
                        </div>

                        <div class="relative flex-1 w-full min-h-[330px]">
                            <canvas id="weeklyConsistencyChart"></canvas>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Insights -->
            <div>
                <x-card class="h-full">
                    <div class="p-6 flex flex-col justify-between h-full space-y-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">Productivity Insights</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Highlight performa utamamu.</p>
                        </div>

                        <div class="space-y-4 my-auto">
                            <!-- Current Streak -->
                            <div class="flex items-center justify-between p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base">🔥</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800">Current Streak</h4>
                                        <p class="text-[10px] text-slate-400">Beruntun saat ini</p>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-slate-900">
                                    {{ $currentStreak }} {{ $currentStreak == 1 ? 'Day' : 'Days' }}
                                </span>
                            </div>

                            <!-- Best Streak -->
                            <div class="flex items-center justify-between p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">🏆</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800">Best Streak</h4>
                                        <p class="text-[10px] text-slate-400">Rekor tertinggi</p>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-slate-900">
                                    {{ $bestStreak ?? 0 }} {{ ($bestStreak ?? 0) == 1 ? 'Day' : 'Days' }}
                                </span>
                            </div>

                            <!-- Most Active -->
                            <div class="flex items-center justify-between p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base">🏃</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800">Most Active</h4>
                                        <p class="text-[10px] text-slate-400">Kategori favorit</p>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-slate-900 truncate max-w-[90px] text-right">{{ $mostActiveCategory }}</span>
                            </div>

                            <!-- Smart Insight -->
                            <div class="p-3.5 bg-gradient-to-br from-emerald-50/60 to-sage-100/40 rounded-2xl border border-emerald-200/60 space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-emerald-200/60 text-emerald-800 flex items-center justify-center font-bold text-xs">💡</div>
                                    <h4 class="text-xs font-bold text-emerald-900">Smart Insight</h4>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $smartInsight }}</p>
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <span class="text-[10px] text-slate-400 font-medium">HabitBuilder Analytics v2.5</span>
                        </div>
                    </div>
                </x-card>
            </div>

        </div>

        <!-- Advanced Calendar & Monthly Analytics -->
        <x-card class="relative overflow-hidden bg-gradient-to-br from-white via-white to-emerald-50/30 border-emerald-100/60">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-100/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative p-6 space-y-6">
                <!-- Header Kalender dengan Navigasi Bulan -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Advanced Consistency Calendar</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Analisis mendalam performa harian per bulan untuk evaluasi jangka panjang.</p>
                    </div>
                    
                    <!-- Tombol Navigasi Bulan (Prev / Next) -->
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <a href="{{ route('analytics.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                           class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors shadow-xs" title="Bulan Sebelumnya">
                            &larr;
                        </a>
                        <span class="text-xs font-bold text-emerald-900 bg-emerald-100/70 border border-emerald-200 px-4 py-2 rounded-xl flex items-center gap-1.5 shadow-xs">
                            📅 {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                        </span>
                        <a href="{{ route('analytics.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                           class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors shadow-xs" title="Bulan Berikutnya">
                            &rarr;
                        </a>
                    </div>
                </div>

                <!-- Statistik Ringkasan Khusus Bulan Ini -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div class="flex items-center justify-between p-3.5 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                        <div>
                            <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Perfect Days</p>
                            <h4 class="text-base font-extrabold text-emerald-900 mt-0.5">{{ $completedDaysCount ?? 0 }} Hari</h4>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-emerald-200/60 text-emerald-800 flex items-center justify-center text-sm font-bold">🟢</div>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-amber-50/50 rounded-2xl border border-amber-100">
                        <div>
                            <p class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Partial Days</p>
                            <h4 class="text-base font-extrabold text-amber-900 mt-0.5">{{ $partialDaysCount ?? 0 }} Hari</h4>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-amber-200/60 text-amber-800 flex items-center justify-center text-sm font-bold">🟡</div>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-rose-50/50 rounded-2xl border border-rose-100">
                        <div>
                            <p class="text-[10px] font-bold text-rose-800 uppercase tracking-wider">Missed Days</p>
                            <h4 class="text-base font-extrabold text-rose-900 mt-0.5">{{ $missedDaysCount ?? 0 }} Hari</h4>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-rose-200/60 text-rose-800 flex items-center justify-center text-sm font-bold">🔴</div>
                    </div>
                </div>

                <!-- Grid Kalender -->
                <div class="space-y-2 pt-2">
                    <div class="grid grid-cols-7 gap-1.5 text-center text-xs font-bold text-slate-400 pb-1">
                        <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                    </div>
                    <div class="grid grid-cols-7 gap-1.5">
                        @foreach ($calendarData as $day => $data)
                        @php
                        $bgClass = match($data['status']) {
                            'green' => 'bg-emeraldAction text-white font-bold shadow-xs',
                            'yellow' => 'bg-amber-300 text-slate-900 font-bold shadow-xs',
                            'red' => 'bg-rose-500 text-white font-bold shadow-xs',
                            default => 'bg-white text-slate-400 border border-slate-200/80 hover:bg-slate-50'
                        };
                        $isToday = ($year == now()->year && $month == now()->month && $day == now()->day);
                        $todayRing = $isToday ? 'ring-2 ring-emerald-600 ring-offset-1 font-extrabold shadow-sm' : '';
                        @endphp
                        <div class="h-9 rounded-xl flex items-center justify-center text-xs transition-all duration-200 {{ $bgClass }} {{ $todayRing }}" title="Day {{ $day }}: {{ $data['label'] }}">
                            {{ $day }}
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Keterangan Legend -->
                <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-600 pt-3 border-t border-slate-100">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-lg bg-emeraldAction"></span> Completed (100%)</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-lg bg-amber-300"></span> Partial</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-lg bg-rose-500"></span> Missed</span>
                </div>
            </div>
        </x-card>

    </x-container>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('weeklyConsistencyChart').getContext('2d');
            const rawData = @json($weeklyData ?? []);

            if (rawData.length > 0) {
                const labels = rawData.map(item => item.day);
                const dataPercentages = rawData.map(item => item.percentage);
                
                // Pewarnaan batang
                const bgColors = dataPercentages.map(p => {
                    if (p === 100) return '#10B981'; 
                    if (p > 0) return '#F59E0B';    
                    return '#E2E8F0';              
                });

                const hoverBgColors = dataPercentages.map(p => {
                    if (p === 100) return '#059669';
                    if (p > 0) return '#D97706';
                    return '#CBD5E1';
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Completion Rate (%)',
                            data: dataPercentages,
                            backgroundColor: bgColors,
                            hoverBackgroundColor: hoverBgColors,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.55,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: {
                                    size: 13,
                                    family: "'Inter', sans-serif"
                                },
                                bodyFont: {
                                    size: 14,
                                    weight: 'bold',
                                    family: "'Inter', sans-serif"
                                },
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + '% Selesai';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    stepSize: 25,
                                    color: '#94a3b8',
                                    font: {
                                        size: 11,
                                        family: "'Inter', sans-serif"
                                    },
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false,
                                    borderDash: [5, 5]
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 12,
                                        weight: '500',
                                        family: "'Inter', sans-serif"
                                    }
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        },
                        animation: {
                            y: {
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.app>