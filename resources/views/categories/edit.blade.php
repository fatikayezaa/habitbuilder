<x-layouts.app>
    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto font-sans text-slate-800 space-y-8">
        
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('categories.index') }}" class="w-8 h-8 flex items-center justify-center bg-white text-slate-700 rounded-xl hover:bg-slate-100 transition-colors shadow-xs border border-slate-200" title="Kembali">
                    <span class="font-bold">&larr;</span>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Kategori</h1>
            </div>
            <p class="text-slate-500 text-sm ml-11">Perbarui label dan warna kategorimu.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border border-slate-200 bg-slate-50 p-3 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all outline-none" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Warna Aksen</label>
                        <div class="flex items-center gap-4">
                            <div class="p-1.5 border border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                <input type="color" name="color" value="{{ old('color', $category->color) }}" class="w-10 h-10 border-0 rounded-lg cursor-pointer bg-transparent" required>
                            </div>
                            <span class="text-xs font-medium text-slate-500">Pilih warna penanda</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Icon</label>
                        <input type="hidden" name="icon" id="selected-icon" value="{{ old('icon', $category->icon) }}" required>
                        
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl inline-block">
                            <div class="grid grid-cols-5 gap-2.5">
                                @php
                                    $emojis = ['🏃', '📚', '💻', '💰', '🍎', '✈️', '🧘', '🎨', '💧', '🔥'];
                                    $currentIcon = old('icon', $category->icon);
                                    
                                    if(!in_array($currentIcon, $emojis) && $currentIcon) {
                                        array_unshift($emojis, $currentIcon);
                                        array_pop($emojis); 
                                    }
                                @endphp

                                @foreach($emojis as $emoji)
                                    <button type="button" 
                                        class="emoji-btn w-10 h-10 flex items-center justify-center text-xl rounded-xl border-2 transition-all duration-200 focus:outline-none hover:scale-105 {{ $currentIcon == $emoji ? 'bg-emerald-50 border-emerald-500 shadow-sm scale-105' : 'bg-transparent border-transparent hover:bg-slate-200' }}" 
                                        data-emoji="{{ $emoji }}">
                                        {{ $emoji }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emeraldAction text-white py-3.5 rounded-xl font-bold hover:bg-[#0F6E52] transition-all duration-300 shadow-md flex items-center justify-center mt-6">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emojiBtns = document.querySelectorAll('.emoji-btn');
            const hiddenInput = document.getElementById('selected-icon');

            emojiBtns.forEach(btn => {
                btn.addEventListener('click', function () {
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