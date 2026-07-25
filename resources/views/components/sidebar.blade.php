<aside class="w-64 min-h-screen bg-[#D4E6DF] dark:bg-slate-900 border-r border-[#C8DDD5] dark:border-slate-800 flex flex-col transition-all duration-300 shadow-sm">
    
    <!-- Logo -->
    <div class="h-24 flex items-center px-6 border-b border-[#C8DDD5] dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <!-- Ikon yang tajam -->
            <img src="{{ asset('images/icon_habit.png') }}" alt="HabitBuilder Icon" class="h-12 w-12 object-contain">
            
            <!-- Teks dengan Typography -->
            <div class="flex flex-col">
                <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                    HabitBuilder
                </span>
                <span class="text-[10px] uppercase tracking-widest text-emerald-800 dark:text-emerald-400 font-semibold">
                    Build Better Habits
                </span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            🏠 Dashboard
        </x-nav-link>

        <x-nav-link href="{{ route('habits.index') }}" :active="request()->routeIs('habits.*')">
            ✅ Habits
        </x-nav-link>

        <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')">
            📂 Categories
        </x-nav-link>

        <x-nav-link href="{{ route('analytics.index') }}" :active="request()->routeIs('analytics.*')">
            📊 Analytics
        </x-nav-link>
    </nav>

    <!-- Bottom Actions -->
    <div class="px-4 py-6 border-t border-[#C8DDD5] dark:border-slate-800">
        <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
            👤 Profile
        </x-nav-link>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 mt-2 text-sm text-slate-700 dark:text-slate-400 hover:text-emeraldAction dark:hover:text-emerald-400 font-medium transition-colors rounded-xl hover:bg-white/50">
                🚪 Logout
            </button>
        </form>
    </div>

</aside>