@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="group flex items-center pl-6 pr-4 py-2 text-sm font-medium rounded-md
          {{ $active
             ? 'text-blue-600 dark:text-blue-300 font-semibold'
             : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white'
          }}">
    <span class="mr-3 w-1.5 h-1.5 rounded-full
                 {{ $active ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-500' }}">
    </span>
    <span>{{ $slot }}</span>
</a>