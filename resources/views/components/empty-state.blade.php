@props(['title', 'message', 'icon'])

<div class="flex flex-col items-center">
    <i class="mb-4 text-5xl fas {{ $icon }} opacity-50"></i>
    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $title }}</p>
    <p class="text-gray-500 dark:text-gray-400">{{ $message }}</p>
</div>