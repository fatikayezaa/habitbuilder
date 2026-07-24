<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HabitBuilder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-slate-50 min-h-screen flex">
    
    <!-- KIRI: Visual Branding (Premium Gradient & Glassmorphism) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-700 to-violet-600 items-center justify-center">
        <!-- Efek Cahaya / Ornamen Abstrak -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-500 rounded-full mix-blend-screen filter blur-[100px] opacity-40"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-screen filter blur-[100px] opacity-40"></div>
        
        <div class="relative z-10 px-12 text-center flex flex-col items-center">
            <!-- Ikon Petir (Semangat) -->
            <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-md mb-8 border border-white/20 shadow-xl">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4">HabitBuilder.</h1>
            <p class="text-lg text-indigo-100 max-w-md mx-auto leading-relaxed">
                "Kesuksesan bukanlah tindakan sesekali, melainkan kebiasaan yang dibangun secara konsisten setiap harinya."
            </p>
        </div>
    </div>

    <!-- KANAN: Form Area Modern -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
        <div class="w-full max-w-md">
            
            <!-- Logo Mobile -->
            <div class="lg:hidden mb-8 flex justify-center">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-600/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>

            <div class="text-center lg:text-left mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-500 font-medium">Silakan masuk untuk melanjutkan progresmu.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Input Email Soft Style -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="block w-full px-4 py-3.5 bg-slate-100 border border-transparent rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200"
                        value="{{ old('email') }}" placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Input Password Soft Style -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Lupa password?</a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-4 py-3.5 bg-slate-100 border border-transparent rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Checkbox -->
                <div class="flex items-center pt-2">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer transition-all">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-600 cursor-pointer font-medium">Ingat saya selama 30 hari</label>
                </div>

                <!-- Button 3D Hover Effect -->
                <button type="submit"
                    class="w-full mt-4 flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-600/30 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    Masuk ke Dashboard
                </button>
            </form>

            <p class="mt-10 text-center text-sm text-slate-600 font-medium">
                Belum mulai membangun kebiasaan? 
                <a href="{{ route('register') }}" class="font-extrabold text-indigo-600 hover:text-indigo-800 transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>
    
</body>
</html>