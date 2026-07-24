<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8">
    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">
        @php
            $hour = \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('H');
            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Good Morning';
            } elseif ($hour >= 12 && $hour < 15) {
                $greeting = 'Good Afternoon';
            } elseif ($hour >= 15 && $hour < 18) {
                $greeting = 'Good Evening';
            } else {
                $greeting = 'Good Night';
            }
        @endphp
        
        {{ $greeting }}, {{ Auth::user()->name }} 👋
    </h2>
    <div class="flex items-center space-x-6 w-1/3 justify-end">
        <input type="text" placeholder="Search habits..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button class="text-slate-500 hover:text-indigo-600">🔔</button>
        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-sm cursor-pointer">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>