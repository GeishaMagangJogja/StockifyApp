@props(['icon', 'color', 'title', 'value'])

@php
    $colors = [
        'blue' => 'bg-blue-100 text-blue-500 dark:bg-blue-900/30',
        'green' => 'bg-green-100 text-green-500 dark:bg-green-900/30',
        'yellow' => 'bg-yellow-100 text-yellow-500 dark:bg-yellow-900/30',
        'red' => 'bg-red-100 text-red-500 dark:bg-red-900/30',
    ];
    $colorClasses = $colors[$color] ?? $colors['blue'];
@endphp

<div class="p-6 bg-white transition-shadow duration-300 rounded-xl shadow-lg dark:bg-slate-800 hover:shadow-2xl">
    <div class="flex items-start justify-between">
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h4>
            <p class="mt-1 text-4xl font-bold text-gray-800 dark:text-white">{{ $value }}</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $colorClasses }}">
            <i class="text-xl fas {{ $icon }}"></i>
        </div>
    </div>
</div>