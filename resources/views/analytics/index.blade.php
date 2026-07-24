<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header Halaman Analytics (Tombol Kembali Dipindah ke Kiri) -->
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-8 h-8 flex items-center justify-center bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 hover:text-slate-900 transition-colors" title="Kembali ke Dashboard">
                    <span class="font-bold">&larr;</span>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Performance Analytics</h1>
            </div>
            <p class="text-slate-500 text-sm ml-11">Analisis mendalam mengenai konsistensi dan produktivitas kebiasaanmu.</p>
        </div>

        <!-- Stat Cards Ringkasan Performa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-stat-card title="Total Check-Ins" value="{{ $totalCompletedLogs ?? 0 }} Times" icon="📊" color="emerald" />
            <x-stat-card title="Active Habits" value="{{ $totalHabits ?? 0 }} Habits" icon="🎯" color="indigo" />
            <x-stat-card title="Consistency Score" value="{{ $consistencyScore ?? 0 }}%" icon="⭐" color="amber" />
        </div>

        <!-- Main Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Kolom Kiri: Grafik Tren Mingguan -->
            <div class="lg:col-span-2 space-y-8">
                <x-card>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">Weekly Consistency Trend</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Grafik penyelesaian habit dalam 7 hari terakhir.</p>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 bg-slate-50 text-slate-600 border border-slate-200 rounded-lg">Last 7 Days</span>
                        </div>

                        <!-- Bar Chart (Menggunakan Chart.js) -->
                        <div class="relative h-64 w-full mt-4">
                            <canvas id="weeklyConsistencyChart"></canvas>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Kolom Kanan: Productivity Insights (100% Data Riil) -->
            <div class="space-y-8">
                <x-card>
                    <div class="p-6">
                        <h3 class="font-bold text-slate-900">Productivity Insights</h3>
                        <p class="text-xs text-slate-500 mt-0.5 mb-6">Highlight performa utamamu.</p>

                        <div class="space-y-3">
                            <!-- Metrik 1: Current Streak -->
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">🔥</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800">Current Streak</h4>
                                        <p class="text-[10px] text-slate-400">Beruntun saat ini</p>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-slate-900">{{ $currentStreak }} Days</span>
                            </div>

                            <!-- Metrik 2: Most Active Category -->
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-base">🏃</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800">Most Active</h4>
                                        <p class="text-[10px] text-slate-400">Kategori favorit</p>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-slate-900 truncate max-w-[90px] text-right">{{ $mostActiveCategory }}</span>
                            </div>

                            <!-- Insight Deskriptif Berbasis Data -->
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2 mt-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">💡</div>
                                    <h4 class="text-xs font-bold text-slate-800">Smart Insight</h4>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $smartInsight }}</p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

        </div>

    </x-container>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('weeklyConsistencyChart').getContext('2d');
            
            // Ambil data real dari Laravel
            const rawData = @json($weeklyData ?? []);
            
            if (rawData.length > 0) {
                const labels = rawData.map(item => item.day);
                const dataPercentages = rawData.map(item => item.percentage);
                
                // Hari ini warnanya beda
                const bgColors = rawData.map(item => item.is_today ? '#10b981' : '#6366f1');
                const hoverBgColors = rawData.map(item => item.is_today ? '#059669' : '#4f46e5');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Completion Rate (%)',
                            data: dataPercentages,
                            backgroundColor: bgColors,
                            hoverBackgroundColor: hoverBgColors,
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { size: 13, family: "'Inter', sans-serif" },
                                bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
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
                                    font: { size: 11, family: "'Inter', sans-serif" },
                                    callback: function(value) { return value + '%'; }
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
                                    font: { size: 12, weight: '500', family: "'Inter', sans-serif" }
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        },
                        animation: {
                            y: { duration: 1000, easing: 'easeOutQuart' }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.app>