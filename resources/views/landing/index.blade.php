<x-layouts.guest title="HabitBuilder - Build Better Habits">
    
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600 tracking-tight">HabitBuilder</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-slate-600 hover:text-indigo-600 font-medium transition">Features</a>
                    <a href="#pricing" class="text-slate-600 hover:text-indigo-600 font-medium transition">Pricing</a>
                    <a href="#faq" class="text-slate-600 hover:text-indigo-600 font-medium transition">FAQ</a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-600 hover:text-indigo-600 font-medium">Sign in</a>
                    <a href="#" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="mx-auto max-w-3xl text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8">
                Build Better Habits, <span class="text-indigo-600">One Day at a Time.</span>
            </h1>
            <p class="mx-auto max-w-2xl text-lg md:text-xl text-slate-500 mb-10">
                Track your habits, stay consistent, and achieve your goals tanpa tekanan. Mulai perjalanan produktifmu hari ini.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="rounded-xl bg-indigo-600 px-8 py-4 text-lg font-semibold text-white hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Start Tracking Free
                </a>
                <a href="#" class="rounded-xl bg-white px-8 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50 transition border border-slate-200">
                    Watch Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <x-features />

    <!-- Pricing Section -->
    <x-pricing />

    <!-- FAQ Section -->
    <x-faq />

    <!-- Footer -->
    <x-footer />

</x-layouts.guest>