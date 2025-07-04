@props(['type', 'text'])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
    $icon = match($type) {
        'success' => 'fa-check-circle',
        'warning' => 'fa-exclamation-triangle',
        'danger' => 'fa-times-circle',
        default => 'fa-info-circle',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $classes }}">
    <i class="mr-1.5 fas {{ $icon }}"></i>
    {{ $text }}
</span>