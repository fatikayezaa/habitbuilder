<x-layouts.app>
    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto font-sans text-slate-800 space-y-8">

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm font-semibold text-emeraldAction hover:text-[#10684B] transition-colors duration-200">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Form Tambah Kategori -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-lg font-bold mb-6 text-slate-900">Tambah Kategori Baru</h2>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Input: Nama -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Contoh: Olahraga, Belajar, Produktivitas" class="w-full border border-slate-200 bg-slate-50 p-3 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all outline-none" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Input: Color -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Warna Aksen</label>
                        <div class="flex items-center gap-4">
                            <div class="p-1.5 border border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                <input type="color" name="color" value="#157F5C" class="w-10 h-10 border-0 rounded-lg cursor-pointer bg-transparent" required>
                            </div>
                            <span class="text-xs font-medium text-slate-500">Pilih warna penanda</span>
                        </div>
                    </div>

                    <!-- Input: Icon Selector -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Icon</label>
                        <input type="hidden" name="icon" id="selected-icon" value="🏃" required>

                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl inline-block">
                            <div class="grid grid-cols-5 gap-2.5">
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-emerald-50 border-2 border-emerald-500 shadow-sm scale-105 transition-all duration-200 focus:outline-none" data-emoji="🏃">🏃</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="📚">📚</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="💻">💻</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="💰">💰</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="🍎">🍎</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="✈️">✈️</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="🧘">🧘</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="🎨">🎨</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="💧">💧</button>
                                <button type="button" class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl bg-transparent border-2 border-transparent hover:bg-slate-200 hover:scale-105 transition-all duration-200 focus:outline-none" data-emoji="🔥">🔥</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emeraldAction text-white py-3.5 rounded-xl font-bold hover:bg-[#0F6E52] transition-all duration-300 shadow-md flex items-center justify-center gap-2 mt-4">
                    <span>+</span> Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Daftar Kategori -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-5">Daftar Kategori Anda</h2>

            @if(isset($categories) && count($categories) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                <!-- Card Item -->
                <div class="category-card bg-white rounded-2xl border border-slate-100 p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md relative overflow-hidden group">

                    <!-- Color Accent -->
                    <div class="absolute top-0 left-0 w-full h-1.5" style="background-color: {{ $category->color }}"></div>

                    <div class="flex-1 mt-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-slate-50 border border-slate-100 group-hover:bg-white group-hover:shadow-xs transition-all duration-300">
                                <span>{{ $category->icon ?? '📁' }}</span>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full shadow-sm" style="background-color: {{ $category->color }}"></span>
                        </div>

                        <h3 class="font-bold text-lg text-slate-900 mb-0.5 truncate">{{ $category->name }}</h3>
                        <p class="text-xs font-medium text-slate-500">
                            {{ isset($category->habits_count) ? $category->habits_count : 0 }} Habit terhubung
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100/80">
                        <a href="{{ route('categories.edit', $category->id) }}" class="text-slate-400 text-xs font-semibold hover:text-emerald-600 transition-colors flex items-center gap-1.5">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 text-xs font-semibold hover:text-rose-500 transition-colors flex items-center gap-1.5">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3 text-2xl">
                    📂
                </div>
                <h3 class="font-bold text-slate-900 mb-1.5 text-base">Belum ada kategori</h3>
                <p class="text-xs font-medium text-slate-500 max-w-xs mx-auto">Buat kategori pertamamu untuk mulai mengorganisir kebiasaan dengan lebih baik.</p>
            </div>
            @endif
        </div>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emojiBtns = document.querySelectorAll('.emoji-btn');
            const hiddenInput = document.getElementById('selected-icon');

            emojiBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    emojiBtns.forEach(b => {
                        b.classList.remove('bg-emerald-50', 'border-emerald-500', 'scale-105', 'shadow-sm');
                        b.classList.add('bg-transparent', 'border-transparent');
                    });

                    this.classList.remove('bg-transparent', 'border-transparent');
                    this.classList.add('bg-emerald-50', 'border-emerald-500', 'scale-105', 'shadow-sm');

                    hiddenInput.value = this.getAttribute('data-emoji');
                });
            });
        });
    </script>
</x-layouts.app>