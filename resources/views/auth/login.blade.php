<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HabitBuilder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-slate-50 min-h-screen flex">
    
    <!-- KIRI: Visual Branding (Fresh Sage Green Theme) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-[#4A7C59] via-[#3B6448] to-[#274431] items-center justify-center p-12">
        <!-- Efek Cahaya / Ornamen Sage Glow -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-[#D4E6DF] rounded-full mix-blend-screen filter blur-[100px] opacity-35"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-emerald-300 rounded-full mix-blend-screen filter blur-[100px] opacity-25"></div>
        
        <div class="relative z-10 text-center flex flex-col items-center max-w-md space-y-6">
            <!-- Badge Kecil -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-[#D4E6DF] text-xs font-medium tracking-wide shadow-sm">
                <span>✨</span>
                <span>Build Better Habits</span>
            </div>

            <!-- Logo -->
            <div class="w-24 h-24 rounded-2xl bg-white/15 backdrop-blur-md border border-white/30 flex items-center justify-center shadow-2xl p-3">
                <img src="{{ asset('images/icon_habit.png') }}" alt="HabitBuilder Logo" class="w-full h-full object-contain drop-shadow-md">
            </div>

            <div>
                <h1 class="text-4xl font-extrabold text-white tracking-tight">HabitBuilder.</h1>
                <p class="text-sm text-[#D4E6DF] mt-2 font-medium">Transform your daily routine into lifelong success.</p>
            </div>

            <!-- Quote Elegan -->
            <blockquote class="text-emerald-100 text-sm italic font-light leading-relaxed pt-4 border-t border-white/20">
                "We first make our habits, then our habits make us."
            </blockquote>
        </div>
    </div>

    <!-- KANAN: Form Area Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-16 bg-white dark:bg-slate-950">
        <div class="w-full max-w-md">
            
            <!-- Logo Mobile -->
            <div class="lg:hidden mb-8 flex justify-center">
                <div class="p-3 bg-emeraldAction rounded-2xl shadow-lg shadow-emeraldAction/30 text-white w-16 h-16 flex items-center justify-center">
                    <img src="{{ asset('images/icon_habit.png') }}" alt="HabitBuilder Logo" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="text-center lg:text-left mb-8">
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Silakan masuk untuk melanjutkan progresmu.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-emeraldAction focus:ring-4 focus:ring-emeraldAction/10 transition-all duration-200"
                        value="{{ old('email') }}" placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-emeraldAction hover:text-emeraldAction-hover transition-colors">Lupa password?</a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-emeraldAction focus:ring-4 focus:ring-emeraldAction/10 transition-all duration-200"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Checkbox -->
                <div class="flex items-center pt-1">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-emeraldAction focus:ring-emeraldAction border-slate-300 rounded cursor-pointer transition-all">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-600 dark:text-slate-400 cursor-pointer font-medium">Ingat saya selama 30 hari</label>
                </div>

                <!-- Button CTA -->
                <button type="submit"
                    class="w-full mt-2 flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emeraldAction/20 text-sm font-bold text-white bg-emeraldAction hover:bg-emeraldAction-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emeraldAction transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    Masuk ke Dashboard
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400 font-medium">
                Belum mulai membangun kebiasaan? 
                <a href="{{ route('register') }}" class="font-extrabold text-emeraldAction hover:text-emeraldAction-hover transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>
    
</body>
</html>