@props(['active' => false, 'icon'])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="group w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
                   {{ $active
                      ? 'bg-blue-600/10 text-blue-600 dark:text-white dark:bg-blue-600/20'
                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700/50'
                   }}">
        <span class="flex items-center">
            <i class="{{ $icon }} fa-fw w-6 h-6 mr-3 text-lg transition-colors duration-200
                       {{ $active
                          ? 'text-blue-500 dark:text-blue-400'
                          : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200'
                       }}">
            </i>
            <span>{{ $trigger }}</span>
        </span>
        <i class="fas fa-chevron-down text-xs transform transition-transform duration-200" :class="{'rotate-180': open}"></i>
    </button>

    <div x-show="open" x-collapse class="mt-2 pl-5 space-y-1 relative
                                       before:absolute before:left-5 before:top-0 before:h-full before:w-px
                                       before:bg-gray-200 dark:before:bg-slate-700">
        {{ $slot }}
    </div>
</div>