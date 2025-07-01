@props(['href', 'active' => false, 'icon'])

<a href="{{ $href }}"
   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
          {{ $active
             ? 'bg-blue-600/10 text-blue-600 dark:text-white dark:bg-blue-600/20'
             : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700/50'
          }}">
    
    <i class="{{ $icon }} fa-fw w-6 h-6 mr-3 text-lg transition-colors duration-200
              {{ $active
                 ? 'text-blue-500 dark:text-blue-400'
                 : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200'
              }}">
    </i>
    <span>{{ $slot }}</span>
</a>