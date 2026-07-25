<aside class="w-64 min-h-screen bg-gradient-to-b from-[#E2F0EA] via-[#D8E9E1] to-[#D0E4DB] border-r border-[#C8DDD5] flex flex-col shadow-sm">

    <!-- Logo -->
    <div class="h-24 flex items-center px-6 border-b border-[#C8DDD5]/80">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">

            <img
                src="{{ asset('images/icon_habit.png') }}"
                alt="HabitBuilder"
                class="w-12 h-12 object-contain transition-transform duration-300 group-hover:scale-105">

            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">
                    HabitBuilder
                </h1>

                <p class="text-[10px] uppercase tracking-widest text-emeraldAction font-semibold">
                    BUILD BETTER HABITS
                </p>
            </div>

        </a>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">

        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            <span class="bi bi-house-door-fill text-lg"></span>
            <span>Dashboard</span>
        </x-nav-link>

        <x-nav-link href="{{ route('habits.index') }}" :active="request()->routeIs('habits.*')">
            <i class="bi bi-check2-square text-lg"></i>
            <span>Habits</span>
        </x-nav-link>

        <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')">
            <i class="bi bi-folder2-open text-lg"></i>
            <span>Categories</span>
        </x-nav-link>

        <x-nav-link href="{{ route('analytics.index') }}" :active="request()->routeIs('analytics.*')">
            <i class="bi bi-bar-chart-line-fill text-lg"></i>
            <span>Analytics</span>
        </x-nav-link>

    </nav>

    <!-- Bottom -->
    <div class="px-4 py-6 border-t border-[#C8DDD5]/80">

        <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
            <i class="bi bi-person-circle text-lg"></i>
            <span>Profile</span>
        </x-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="mt-2 flex w-full items-center gap-3 rounded-xl px-5 py-3
                       text-slate-700 font-medium
                       transition-all duration-200
                       hover:bg-white/70
                       hover:text-emeraldAction
                       hover:shadow-sm">

                <i class="bi bi-box-arrow-right text-lg"></i>
                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>