@props(['icon', 'color', 'title', 'value'])

@php
    $colors = [
        'cyan' => 'text-cyan-500',
        'green' => 'text-green-500',
        'yellow' => 'text-yellow-500',
        'red' => 'text-red-500',
        'purple' => 'text-purple-500',
    ];
    $colorClasses = $colors[$color] ?? 'text-gray-500';

    // [BARU] Pisahkan prefix (Rp) dari angka
    $prefix = null;
    $numberValue = $value;
    if (is_string($value) && str_starts_with(strtolower($value), 'rp')) {
        $parts = explode(' ', $value, 2);
        $prefix = $parts[0];
        $numberValue = $parts[1] ?? '';
    }
@endphp

<div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
        {{-- [PERBAIKAN] Pastikan ikon tidak lonjong --}}
        <div class="flex items-center justify-center w-8 h-8 flex-shrink-0 rounded-full {{ str_replace('text-', 'bg-', $colorClasses) }} bg-opacity-10 dark:bg-opacity-20">
             <i class="text-base fas {{ $icon }} {{ $colorClasses }}"></i>
        </div>
    </div>
    <div class="mt-2">
        {{-- [PERBAIKAN] Gunakan flexbox untuk menyatukan prefix dan angka --}}
        @if($prefix)
            <div class="flex items-baseline text-gray-900 dark:text-white">
                <span class="text-2xl font-semibold">{{ $prefix }}</span>
                <span class="ml-1 text-3xl font-bold tracking-tight">{{ $numberValue }}</span>
            </div>
        @else
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $numberValue }}</p>
        @endif
    </div>
</div>