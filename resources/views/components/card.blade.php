@props(['header' => null])

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    @if($header)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            {{ $header }}
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>