<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-xl font-bold mb-4">Tambah Kategori</h1>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori" class="w-full border p-2 rounded" required>
            <input type="text" name="color" placeholder="Warna (contoh: #FF0000)" class="w-full border p-2 rounded" required>
            <input type="text" name="icon" placeholder="Nama Icon" class="w-full border p-2 rounded" required>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700">Simpan</button>
        </form>

        <h2 class="text-lg font-bold mt-8 mb-4">Daftar Kategori Anda</h2>
        <ul class="space-y-3">
            @foreach($categories as $category)
                <li class="p-4 border rounded-lg bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg" style="color: {{ $category->color }}">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">Warna: {{ $category->color }} | Ikon: {{ $category->icon ?? 'Tidak ada' }}</p>
                    </div>
                    
                    <!-- Tombol Aksi (Edit & Hapus) -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 text-sm font-semibold hover:text-blue-700">Edit</a>
                        
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
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