<x-layouts.app>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm font-semibold text-emeraldAction hover:text-[#10684B] transition-colors duration-200">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Box Utama Form Tambah Habit -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
            <h1 class="text-xl font-bold mb-4 text-gray-800">Tambah Kebiasaan Baru</h1>

            <form action="{{ route('habits.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Habit (Title)</label>
                    <input type="text" name="title" placeholder="Membaca Buku" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kategori</label>
                    <select name="category_id" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Target & Unit -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target (Angka/Teks)</label>
                        <input type="text" name="target" placeholder="Contoh: 10, 30, 1" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan (Unit)</label>
                        <input type="text" name="target_unit" placeholder="Contoh: Halaman, Menit, Kali" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frekuensi</label>
                    <select name="frequency" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer" required>
                        <option value="daily">Harian (Daily)</option>
                        <option value="weekly">Mingguan (Weekly)</option>
                        <option value="weekdays">Hari Kerja (Weekdays)</option>
                        <option value="weekend">Akhir Pekan (Weekend)</option>
                        <option value="one_time">Sekali Selesai (One-Time Task)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" placeholder="Minimal 10 halaman per hari" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-emeraldAction text-white py-3.5 rounded-xl font-bold hover:bg-[#0F6E52] transition-all duration-300 shadow-md flex items-center justify-center gap-2 mt-4">
                    <span>+</span> Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Daftar Habit Anda -->
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Habit Anda</h2>

            @if(isset($habits) && count($habits) > 0)
            <div class="space-y-3">
                @foreach($habits as $habit)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">{{ $habit->title }}</h3>
                        <div class="text-xs text-gray-500 flex flex-wrap items-center gap-2 mt-1">
                            Kategori:
                            @if($habit->category)
                            <span class="px-2 py-0.5 rounded-md text-white text-xs font-bold shadow-xs" style="background-color: {{ $habit->category->color }}">
                                {{ $habit->category->icon }} {{ $habit->category->name }}
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-md text-white text-xs font-bold bg-gray-400">
                                Tanpa Kategori
                            </span>
                            @endif

                            <span>| Target: {{ $habit->target }} {{ $habit->target_unit }} | Frekuensi: {{ ucfirst($habit->frequency) }}</span>
                        </div>
                        @if($habit->description)
                        <p class="text-xs text-gray-600 mt-1.5 italic">
                            "{{ $habit->description }}"
                        </p>
                        @endif
                    </div>

                    <!-- Tombol Aksi (Edit & Hapus) -->
                    <div class="flex items-center gap-4 self-end sm:self-center">
                        <a href="{{ route('habits.edit', $habit->id) }}" class="text-indigo-600 text-sm font-semibold hover:text-indigo-800">Edit</a>

                        <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus habit ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm font-semibold hover:text-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada habit yang terdaftar.</p>
            </div>
            @endif
        </div>

    </div>
</x-layouts.app>