{{-- resources/views/components/sidebar/menu-dropdown-dashboard.blade.php --}}

@props(['icon' => null, 'routeName' => null, 'title' => null])

{{-- 
    1. Tambahkan x-data untuk mengelola state 'open' (terbuka/tertutup).
    2. Inisialisasi 'open' menjadi 'true' jika route saat ini cocok, agar menu tetap terbuka.
--}}
<li x-data="{ open: {{ request()->routeIs($routeName) ? 'true' : 'false' }} }">
    
    {{-- 
        3. Ganti data-collapse-toggle dengan @click untuk mengubah state 'open'.
    --}}
    <button type="button"
        @click="open = !open"
        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
        
        {{-- Anda bisa menambahkan ikon di sini jika mau --}}
        {{-- <i class="fas fa-{{ $icon }} mr-3"></i> --}}

        <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $title }}</span>
        
        {{-- 
            4. Buat ikon panah berputar berdasarkan state 'open'.
        --}}
        <svg class="w-6 h-6 transform transition-transform" x-bind:class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
    </button>
    
    {{-- 
        5. Ganti class 'hidden' dengan x-show dan x-collapse untuk animasi yang halus.
    --}}
    <ul id="{{ $routeName }}" class="py-2 space-y-2" x-show="open" x-collapse>
        {{ $slot }}
    </ul>
</li>