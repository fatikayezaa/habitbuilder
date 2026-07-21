@props(['title', 'value', 'icon', 'color' => 'indigo'])

@php
    $colors = [
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'amber'  => 'bg-amber-50 text-amber-600',
        'emerald'=> 'bg-emerald-50 text-emerald-600',
        'slate'  => 'bg-slate-100 text-slate-600',
    ];
@endphp

<div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $value }}</h3>
        </div>
        <div class="p-3 {{ $colors[$color] ?? $colors['indigo'] }} rounded-xl">
            {{ $icon }}
        </div>
    </div>
</div>