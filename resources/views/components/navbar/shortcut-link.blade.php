@props(['href', 'icon', 'color'])

@php
$colors = [
    'purple' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400',
    'green'  => 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400',
    'blue'   => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
    'red'    => 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400',
    'sky'    => 'bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400',
    'orange' => 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400',
];
$colorClasses = $colors[$color] ?? $colors['blue'];
@endphp

<a href="{{ $href }}" class="group flex flex-col items-center justify-center p-2 text-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
    <div class="flex items-center justify-center w-10 h-10 mb-2 transition-colors rounded-lg {{ $colorClasses }}">
        <i class="{{ $icon }} text-lg"></i>
    </div>
    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $slot }}</p>
</a>