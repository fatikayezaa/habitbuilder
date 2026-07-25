<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">

    <h2 class="text-lg font-semibold text-slate-800">
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

    <div class="flex items-center gap-5 w-1/3 justify-end">

        <!-- Search -->
        <div class="relative w-full">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

            <input
                type="text"
                placeholder="Search habits..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm
                       focus:outline-none
                       focus:ring-2
                       focus:ring-emerald-100
                       focus:border-emeraldAction
                       transition">
        </div>

        <!-- Notification -->
        <button class="text-slate-500 hover:text-emeraldAction transition">
            <i class="bi bi-bell text-xl"></i>
        </button>

        <!-- Avatar -->
        <div class="w-10 h-10 rounded-full bg-emeraldAction text-white font-semibold flex items-center justify-center shadow-sm ring-2 ring-white cursor-pointer">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>

    </div>

</header>