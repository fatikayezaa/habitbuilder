@props(['active' => false])

<a {{ $attributes->merge(['class' => 'flex items-center px-6 py-3 transition-colors duration-200 ' . ($active ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50')]) }}>
    {{ $slot }}
</a>