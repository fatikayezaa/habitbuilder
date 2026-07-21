<!DOCTYPE html>
<html lang="id">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-xl font-bold mb-4">Tambah Kebiasaan Baru</h1>

        <form action="{{ route('habits.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Habit (Title)</label>
                <input type="text" name="title" placeholder="Membaca Buku" class="mt-1 w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kategori</label>
                <select name="category_id" class="mt-1 w-full border p-2 rounded" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tambahan Input Target & Unit agar database tidak error -->
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Target (Angka/Teks)</label>
                    <input type="text" name="target" placeholder="Contoh: 10, 30, 1" class="mt-1 w-full border p-2 rounded" required>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Satuan (Unit)</label>
                    <input type="text" name="target_unit" placeholder="Contoh: Halaman, Menit, Kali" class="mt-1 w-full border p-2 rounded" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Frekuensi</label>
                <!-- Value diubah agar sesuai dengan Enum Database -->
                <select name="frequency" class="mt-1 w-full border p-2 rounded" required>
                    <option value="daily">Harian (Daily)</option>
                    <option value="weekly">Mingguan (Weekly)</option>
                    <option value="weekdays">Hari Kerja (Weekdays)</option>
                    <option value="weekend">Akhir Pekan (Weekend)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" placeholder="Minimal 10 halaman per hari" class="mt-1 w-full border p-2 rounded"></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded font-bold hover:bg-indigo-700">Simpan Habit</button>
        </form>

        <h2 class="text-lg font-bold mt-8 mb-4">Daftar Habit Anda</h2>
        <ul class="space-y-3">
            @foreach($habits as $habit)
            <li class="p-4 border rounded-lg bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">{{ $habit->title }}</h3>
                    <div class="text-sm text-gray-500 flex items-center gap-2 mt-1">
                        Kategori:
                        @if($habit->category)
                        <span class="px-2 py-0.5 rounded-md text-white text-xs font-bold" style="background-color: {{ $habit->category->color }}">
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
                    <p class="text-sm text-gray-600 mt-1 italic">
                        "{{ $habit->description }}"
                    </p>
                    @endif
                </div>

                <!-- Tombol Aksi (Edit & Hapus) -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('habits.edit', $habit->id) }}" class="text-indigo-500 text-sm font-semibold hover:text-indigo-700">Edit</a>

                    <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus habit ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 text-sm font-semibold hover:text-red-700">Hapus</button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</body>

</html>