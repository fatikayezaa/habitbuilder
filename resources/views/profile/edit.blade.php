<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Pengguna</h1>
                <p class="text-slate-500 text-sm mt-0.5">Kelola informasi pribadi dan keamanan kata sandi akunmu.</p>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                <span>✅</span> Profil berhasil diperbarui!
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                <span>✅</span> Kata sandi berhasil diperbarui!
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- CARD 1: UPDATE PROFILE INFO & STATS -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                
                <!-- Bagian Atas: Avatar, Info & Form -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white font-bold text-2xl flex items-center justify-center shadow-md">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[10px] font-semibold px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full">
                                    Member
                                </span>
                                <span class="text-[10px] font-semibold px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full flex items-center gap-1">
                                    ✓ Verified Email
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-sm text-slate-900 mb-1">Informasi Akun</h4>
                        <p class="text-xs text-slate-500">Perbarui nama lengkap dan alamat email profilmu.</p>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 transition-all">
                            @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 transition-all">
                            @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold text-xs rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bagian Bawah: Meta Info -->
                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Bergabung sejak: <strong class="text-slate-600">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Juli 2026' }}</strong></span>
                    <span>Status: <strong class="text-emerald-600">Active</strong></span>
                </div>
            </div>

            <!-- CARD 2: UPDATE PASSWORD DENGAN STRENGTH & TOGGLE -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Ubah Kata Sandi</h3>
                    <p class="text-xs text-slate-500">Perbarui kata sandi secara berkala untuk menjaga keamanan akun.</p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 pr-10">
                            <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 top-2.5 text-xs text-slate-400 hover:text-slate-600">👁</button>
                        </div>
                        @error('current_password', 'updatePassword') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="new_password" required oninput="checkPasswordStrength(this.value)" class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 pr-10">
                            <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-3 top-2.5 text-xs text-slate-400 hover:text-slate-600">👁</button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2 space-y-1">
                            <div class="flex gap-1 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div id="strength-bar" class="w-0 h-full transition-all duration-300"></div>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span id="strength-text" class="text-slate-400">Minimal 8 karakter</span>
                            </div>
                        </div>

                        @error('password', 'updatePassword') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirm_password" required class="w-full px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 pr-10">
                            <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute right-3 top-2.5 text-xs text-slate-400 hover:text-slate-600">👁</button>
                        </div>
                        @error('password_confirmation', 'updatePassword') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold text-xs rounded-xl hover:bg-emerald-700 transition-all shadow-sm mt-4">
                        Perbarui Sandi
                    </button>
                </form>
            </div>

        </div>

    </x-container>

    <!-- Script Pendukung untuk Show/Hide Password & Strength Meter -->
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁';
            }
        }

        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            if (password.length === 0) {
                bar.style.width = '0%';
                bar.className = 'w-0 h-full transition-all duration-300';
                text.textContent = 'Minimal 8 karakter';
                text.className = 'text-slate-400';
            } else if (strength <= 2) {
                bar.style.width = '33%';
                bar.className = 'w-1/3 h-full bg-amber-500 transition-all duration-300';
                text.textContent = 'Lemah';
                text.className = 'text-amber-600 font-semibold';
            } else if (strength === 3) {
                bar.style.width = '66%';
                bar.className = 'w-2/3 h-full bg-blue-500 transition-all duration-300';
                text.textContent = 'Sedang';
                text.className = 'text-blue-600 font-semibold';
            } else {
                bar.style.width = '100%';
                bar.className = 'w-full h-full bg-emerald-500 transition-all duration-300';
                text.textContent = 'Kuat ✨';
                text.className = 'text-emerald-600 font-semibold';
            }
        }
    </script>
</x-layouts.app>