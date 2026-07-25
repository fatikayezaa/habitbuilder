<x-layouts.app>
    <x-container class="py-8 space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Aplikasi</h1>
                <p class="text-slate-500 text-sm mt-0.5">Kelola preferensi tampilan, zona waktu, bahasa, dan notifikasi sistem.</p>
            </div>
        </div>

        @if (session('status') === 'settings-updated')
            <div class="p-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-bold">✓</span>
                <div>
                    <span class="font-bold">Berhasil!</span> Pengaturan aplikasi Anda telah diperbarui dan disimpan ke sistem.
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                <!-- CARD 1: INFORMASI AKUN & PREFERENSI UMUM -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Preferensi Umum</h3>
                        <p class="text-xs text-slate-500">Informasi akun dan konfigurasi dasar aplikasi.</p>
                    </div>

                    <!-- Profile Summary (Read-Only) -->
                    <div class="p-4 bg-slate-50/70 rounded-xl space-y-2 border border-slate-100">
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Nama Akun</span>
                            <span class="text-sm font-bold text-slate-800">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Alamat Email</span>
                            <span class="text-sm font-medium text-slate-600">{{ $user->email }}</span>
                        </div>
                    </div>

                    <!-- Theme -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tema Tampilan</label>
                        <select name="theme" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 bg-white text-slate-700">
                            <option value="system" {{ old('theme', $setting->theme) === 'system' ? 'selected' : '' }}>Ikuti Sistem (System Default)</option>
                            <option value="light" {{ old('theme', $setting->theme) === 'light' ? 'selected' : '' }}>Terang (Light)</option>
                            <option value="dark" {{ old('theme', $setting->theme) === 'dark' ? 'selected' : '' }}>Gelap (Dark)</option>
                        </select>
                        @error('theme') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Zona Waktu (Timezone)</label>
                        <select name="timezone" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 bg-white text-slate-700">
                            <option value="Asia/Jakarta" {{ old('timezone', $setting->timezone) === 'Asia/Jakarta' ? 'selected' : '' }}>(GMT+7) Jakarta / W.I.B</option>
                            <option value="Asia/Makassar" {{ old('timezone', $setting->timezone) === 'Asia/Makassar' ? 'selected' : '' }}>(GMT+8) Makassar / W.I.T.A</option>
                            <option value="Asia/Jayapura" {{ old('timezone', $setting->timezone) === 'Asia/Jayapura' ? 'selected' : '' }}>(GMT+9) Jayapura / W.I.T</option>
                        </select>
                        @error('timezone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bahasa</label>
                        <select name="language" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 bg-white text-slate-700">
                            <option value="id" {{ old('language', $setting->language) === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            <option value="en" {{ old('language', $setting->language) === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                        @error('language') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- CARD 2: PENGATURAN NOTIFIKASI & AKSI SIMPAN -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Pengaturan Notifikasi</h3>
                        <p class="text-xs text-slate-500">Atur cara aplikasi mengingatkan kebiasaan harianmu.</p>
                    </div>

                    <div class="space-y-4 pt-1">
                        <!-- Reminder Notification Toggle -->
                        <div class="flex items-center justify-between p-4 bg-slate-50/70 rounded-xl border border-slate-100">
                            <div>
                                <span class="block text-sm font-bold text-slate-800">Notifikasi Pengingat</span>
                                <span class="text-xs text-slate-500">Terima pengingat harian untuk *check-in habit*.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="reminder_notification" value="1" {{ old('reminder_notification', $setting->reminder_notification) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- Email Notification Toggle -->
                        <div class="flex items-center justify-between p-4 bg-slate-50/70 rounded-xl border border-slate-100">
                            <div>
                                <span class="block text-sm font-bold text-slate-800">Notifikasi Email</span>
                                <span class="text-xs text-slate-500">Kirim laporan progres mingguan melalui email.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_notification" value="1" {{ old('email_notification', $setting->email_notification) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box untuk Skripsi / Dokumentasi -->
                    <div class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl text-xs text-emerald-900 space-y-1">
                        <span class="font-bold block">💡 Informasi Sistem</span>
                        <p class="text-slate-600 leading-relaxed">Data pengaturan ini terhubung secara relasional langsung ke database pengguna, memastikan preferensi Anda tetap tersimpan di perangkat mana pun.</p>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-semibold text-xs rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                            Simpan Perubahan Pengaturan
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </x-container>
</x-layouts.app>