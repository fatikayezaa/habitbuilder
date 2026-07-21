<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Edit Kategori</h1>
            <a href="{{ url('/categories') }}" class="text-gray-500 hover:text-gray-700 text-sm">Kembali</a>
        </div>
        
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <!-- Wajib untuk proses update di Laravel -->
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" value="{{ $category->name }}" class="mt-1 w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Warna (Hex Code)</label>
                <input type="text" name="color" value="{{ $category->color }}" class="mt-1 w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ikon (Opsional)</label>
                <input type="text" name="icon" value="{{ $category->icon }}" class="mt-1 w-full border p-2 rounded">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold hover:bg-blue-700">Update Kategori</button>
        </form>
    </div>
</body>
</html>