<x-layouts.app>
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('habits.index') }}"
                class="inline-flex items-center text-sm font-semibold text-emeraldAction hover:text-[#10684B] transition-colors duration-200">
                &larr; Kembali ke Daftar Habit
            </a>
        </div>

        <!-- Box Utama Form Edit Habit -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
            <h1 class="text-xl font-bold mb-4 text-gray-800">Edit Kebiasaan</h1>

            <form action="{{ route('habits.update', $habit->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Habit (Title)</label>
                    <input type="text" name="title" value="{{ old('title', $habit->title) }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kategori</label>
                    <select name="category_id" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer" required>
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $habit->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Target & Unit -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target (Angka/Teks)</label>
                        <input type="text" name="target" value="{{ old('target', $habit->target) }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan (Unit)</label>
                        <input type="text" name="target_unit" value="{{ old('target_unit', $habit->target_unit) }}" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frekuensi</label>
                    <select name="frequency" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer" required>
                        <option value="daily" {{ $habit->frequency == 'daily' ? 'selected' : '' }}>Harian (Daily)</option>
                        <option value="weekly" {{ $habit->frequency == 'weekly' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                        <option value="weekdays" {{ $habit->frequency == 'weekdays' ? 'selected' : '' }}>Hari Kerja (Weekdays)</option>
                        <option value="weekend" {{ $habit->frequency == 'weekend' ? 'selected' : '' }}>Akhir Pekan (Weekend)</option>
                        <option value="one_time" {{ $habit->frequency == 'one_time' ? 'selected' : '' }}>Sekali Selesai (One-Time Task)</option>
                    </select>
                </div>

                <!-- Pilihan Hari Spesifik (Checkbox) -->
                @php
                    $selectedDays = $habit->schedules ? $habit->schedules->pluck('day_of_week')->toArray() : [];
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Hari Spesifik (Opsional - Jika ingin kustom)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Monday" {{ in_array('Monday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Senin</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Tuesday" {{ in_array('Tuesday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Selasa</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Wednesday" {{ in_array('Wednesday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Rabu</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Thursday" {{ in_array('Thursday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Kamis</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Friday" {{ in_array('Friday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Jumat</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Saturday" {{ in_array('Saturday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Sabtu</span></label>
                        <label class="inline-flex items-center"><input type="checkbox" name="days[]" value="Sunday" {{ in_array('Sunday', $selectedDays) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm"> <span class="ml-2 text-sm text-gray-700">Minggu</span></label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description', $habit->description) }}</textarea>
                </div>

                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-emeraldAction text-white font-semibold
           hover:bg-[#126B4D] transition duration-200 shadow-md hover:scale-[1.01]">
                    Simpan Perubahan Habit
                </button>
            </form>
        </div>

    </div>
</x-layouts.app>