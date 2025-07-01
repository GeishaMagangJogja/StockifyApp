@props(['href', 'active' => false, 'icon'])

<a href="{{ $href }}"
   x-data="{ tooltip: '{{ $slot }}' }"
   x-tooltip="tooltip"
   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
          {{ $active
             ? 'bg-blue-600/10 text-blue-600 dark:text-white dark:bg-blue-600/20'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700/50'
          }}"
    :class="{ 'justify-center': sidebarCollapsed }">
    
    <i class="{{ $icon }} fa-fw w-6 h-6 mr-3 text-lg transition-colors duration-200
              {{ $active ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200' }}"
       :class="{ 'mr-0': sidebarCollapsed }">
    </i>
    <span class="transition-opacity duration-200" :class="{ 'lg:hidden': sidebarCollapsed }">{{ $slot }}</span>
</a>