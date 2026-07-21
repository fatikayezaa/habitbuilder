<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Edit Habit</h1>
            <a href="{{ url('/habits') }}" class="text-gray-500 hover:text-gray-700 text-sm">Kembali</a>
        </div>
        
        <form action="{{ route('habits.update', $habit->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Habit (Title)</label>
                <input type="text" name="title" value="{{ $habit->title }}" class="mt-1 w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kategori</label>
                <select name="category_id" class="mt-1 w-full border p-2 rounded" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $habit->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Target (Angka/Teks)</label>
                    <input type="text" name="target" value="{{ $habit->target }}" class="mt-1 w-full border p-2 rounded" required>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Satuan (Unit)</label>
                    <input type="text" name="target_unit" value="{{ $habit->target_unit }}" class="mt-1 w-full border p-2 rounded" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Frekuensi</label>
                <select name="frequency" class="mt-1 w-full border p-2 rounded" required>
                    <option value="daily" {{ $habit->frequency == 'daily' ? 'selected' : '' }}>Harian (Daily)</option>
                    <option value="weekly" {{ $habit->frequency == 'weekly' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                    <option value="weekdays" {{ $habit->frequency == 'weekdays' ? 'selected' : '' }}>Hari Kerja (Weekdays)</option>
                    <option value="weekend" {{ $habit->frequency == 'weekend' ? 'selected' : '' }}>Akhir Pekan (Weekend)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" class="mt-1 w-full border p-2 rounded">{{ $habit->description }}</textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded font-bold hover:bg-indigo-700">Update Habit</button>
        </form>
    </div>
</body>
</html>