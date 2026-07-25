@props(['active' => false])

<a {{ $attributes->merge([
    'class' =>
        'flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-200 font-medium ' .
        ($active
            ? 'bg-white text-emeraldAction border-l-4 border-emeraldAction shadow-sm'
            : 'text-slate-700 hover:bg-white/70 hover:text-emeraldAction')
]) }}>
    {{ $slot }}
</a>