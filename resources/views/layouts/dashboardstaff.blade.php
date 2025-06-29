<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false, sidebarOpen: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true'" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Sistem Gudang') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden">
            <div class="fixed inset-0 bg-black opacity-25" @click="sidebarOpen = false"></div>
        </div>

        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
               x-bind:class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-blue-600 dark:bg-blue-700">
                <h1 class="text-xl font-bold text-white">Stockfy</h1>
            </div>

            <!-- User Info -->
            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                            @if(auth()->check())
                                {{ substr(auth()->user()->name, 0, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->role ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigasi Sidebar Khusus Staff Gudang -->
            <nav class="mt-4 px-4">
                <div class="space-y-1">

                    <!-- 1. Dashboard Tugas -->
                    <a href="{{ route('staff.dashboard') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
                              {{ request()->routeIs('staff.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-clipboard-list w-5 text-center mr-3"></i>
                        Dashboard Tugas
                    </a>

                    <!-- 2. Manajemen Stok (Dropdown) -->
                    <div x-data="{ open: {{ request()->routeIs('staff.stock.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-boxes-stacked w-5 text-center mr-3"></i>
                                Manajemen Stok
                            </span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200" x-bind:class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-collapse.duration.300ms class="ml-4 mt-1 space-y-1">
                            <span class="block px-3 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400">Barang Masuk</span>
                            <a href="{{ route('staff.stock.incoming.list') }}"
                               class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('staff.stock.incoming.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                Daftar Barang Masuk
                            </a>
                            
                            <span class="block px-3 py-1 mt-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Barang Keluar</span>
                            <a href="{{ route('staff.stock.outgoing.list') }}"
                               class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('staff.stock.outgoing.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                Daftar Barang Keluar
                            </a>
                        </div>
                    </div>

                    <!-- 3. Laporan (Dropdown) -->
                    <div x-data="{ open: {{ request()->routeIs('staff.reports.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-chart-line w-5 text-center mr-3"></i>
                                Laporan
                            </span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200" x-bind:class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-collapse.duration.300ms class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('staff.reports.incoming') }}"
                               class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('staff.reports.incoming') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                Riwayat Stok Masuk
                            </a>
                            <a href="{{ route('staff.reports.outgoing') }}"
                               class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('staff.reports.outgoing') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                Riwayat Stok Keluar
                            </a>
                        </div>
                    </div>

                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg">
                            <i class="fas fa-moon dark:hidden"></i>
                            <i class="fas fa-sun hidden dark:block"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">
                                        @if(auth()->check())
                                            {{ substr(auth()->user()->name, 0, 2) }}
                                        @endif
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Guest' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</p>
                                    </div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

</body>
</html>