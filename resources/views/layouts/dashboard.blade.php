<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Judul halaman dinamis yang mengambil dari config('app.name') --}}
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Stockify') }}</title>
    
    {{-- =================================================================== --}}
    {{-- BARIS YANG DITAMBAHKAN - UNTUK MENAMPILKAN FAVICON DINAMIS --}}
    {{-- =================================================================== --}}
    <link id="favicon" rel="icon" href="{{ get_favicon_url() }}" type="image/x-icon">
    {{-- =================================================================== --}}

    <script>
        // Menerapkan tema dari localStorage SEGERA untuk mencegah FOUC (Flash of Unstyled Content)
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          darkMode: 'class',
          theme: {
            extend: {
              colors: {
                'dark-primary': '#1e293b',   // slate-800
                'dark-secondary': '#0f172a',  // slate-900
                'dark-accent': '#3b82f6',    // blue-500
              }
            }
          }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@ryangjchandler/alpine-tooltip@1.x.x/dist/cdn.min.js"></script>

    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }

        :root {
            /* Warna untuk Light Mode */
            --scrollbar-track: #f1f5f9; /* slate-100 */
            --scrollbar-thumb: #cbd5e1; /* slate-300 */
            --scrollbar-thumb-hover: #94a3b8; /* slate-400 */

            /* Warna khusus untuk sidebar di light mode */
            --sidebar-scrollbar-thumb: #e2e8f0; /* slate-200 */
            --sidebar-scrollbar-thumb-hover: #cbd5e1; /* slate-300 */
        }

        .dark {
            /* Timpa variabel saat Dark Mode aktif */
            --scrollbar-track: #0f172a; /* dark-secondary */
            --scrollbar-thumb: #475569; /* slate-600 */
            --scrollbar-thumb-hover: #64748b; /* slate-500 */
            
            /* Warna khusus untuk sidebar di dark mode */
            --sidebar-scrollbar-thumb: #334155; /* slate-700 */
            --sidebar-scrollbar-thumb-hover: #475569; /* slate-600 */
        }

        /* --- Styling Scrollbar Utama (hanya ditulis sekali) --- */
        /* Untuk Firefox */
        html {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
        }
        /* Untuk WebKit (Chrome, Safari, Edge) */
        html::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        html::-webkit-scrollbar-track {
            background-color: var(--scrollbar-track);
        }
        html::-webkit-scrollbar-thumb {
            background-color: var(--scrollbar-thumb);
            border-radius: 10px;
            border: 2px solid var(--scrollbar-track);
        }
        html::-webkit-scrollbar-thumb:hover {
            background-color: var(--scrollbar-thumb-hover);
        }

        /* --- Styling Scrollbar Khusus (Sidebar) --- */
        /* Untuk Firefox */
        .custom-scrollbar {
            scrollbar-color: var(--sidebar-scrollbar-thumb) transparent;
        }
        /* Untuk WebKit */
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: var(--sidebar-scrollbar-thumb);
            border: 2px solid transparent; /* Reset border */
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            border-color: #1e293b; /* Beri border hanya di dark mode */
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: var(--sidebar-scrollbar-thumb-hover);
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-dark-secondary">
    
    <div x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            toggleSidebarCollapse() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            },
            darkMode: localStorage.getItem('darkMode') === 'true',
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode);
                document.documentElement.classList.toggle('dark', this.darkMode);
            }
         }" 
         x-init="$watch('sidebarCollapsed', value => document.body.classList.toggle('sidebar-collapsed', value))"
         :class="{ 'sidebar-collapsed': sidebarCollapsed }"
         class="flex h-screen overflow-x-hidden">

        {{-- Komponen Navbar dipanggil di sini --}}
        <x-navbar-dashboard />

        {{-- Sidebar HANYA untuk user yang sudah login --}}
        @auth
            <!-- Sidebar Backdrop untuk mobile -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-10 bg-black opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

            <!-- [MODIFIKASI] Class dinamis untuk mengubah lebar sidebar -->
            <aside 
                class="fixed inset-y-0 left-0 z-20 flex flex-col pt-16 transition-[width] ease-in-out transform bg-white shadow-lg dark:bg-dark-primary lg:translate-x-0"
                :class="{
                    'translate-x-0 w-64': sidebarOpen, 
                    '-translate-x-full': !sidebarOpen,
                    'lg:w-64': !sidebarCollapsed,
                    'lg:w-20': sidebarCollapsed
                }">
                
                <div class="flex flex-col flex-1 h-full px-3 pb-4 overflow-y-auto custom-scrollbar">
                    <!-- User Info -->
                    <div class="px-3 py-4" :class="{ 'lg:px-1': sidebarCollapsed }">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="object-cover w-11 h-11 rounded-full ring-2 ring-blue-400" 
                                     src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff' }}" 
                                     alt="{{ Auth::user()->name }}">
                            </div>
                            {{-- [MODIFIKASI] Sembunyikan teks saat sidebar di-collapse --}}
                            <div class="ml-4 transition-opacity" :class="{ 'lg:opacity-0 lg:invisible': sidebarCollapsed }">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                    </div>
    
                    <!-- Navigation -->
                    {{-- [MODIFIKASI] Padding disesuaikan saat collapse --}}
                    <nav class="flex-1 px-1 mt-2" :class="{ 'lg:px-0': sidebarCollapsed }">
                        @if (auth()->user()->role === 'Admin')
                            <x-sidebar.admin-sidebar />
                        @elseif (auth()->user()->role === 'Manajer Gudang')
                            <x-sidebar.manajergudang-sidebar />
                        @elseif (auth()->user()->role === 'Staff Gudang')
                            <x-sidebar.staffgudang-sidebar />
                        @endif
                    </nav>
                </div>
                
                {{-- [MODIFIKASI BARU] Tombol untuk Collapse/Expand Sidebar --}}
                <div class="flex-shrink-0 p-4 border-t border-slate-200 dark:border-slate-700">
                    <button @click="toggleSidebarCollapse" class="flex items-center justify-center w-full p-2 text-gray-500 rounded-lg dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                        <i class="transition-transform fas" :class="{ 'fa-chevron-left': !sidebarCollapsed, 'fa-chevron-right': sidebarCollapsed }"></i>
                        <span class="ml-3 font-medium transition-opacity " :class="{ 'lg:opacity-0 lg:hidden': sidebarCollapsed }">Sembunyikan</span>
                    </button>
                </div>
            </aside>
        @endauth

        {{-- [MODIFIKASI] Margin kiri dinamis untuk konten utama --}}
        <main class="flex-1 pt-16 overflow-y-auto transition-[margin-left]  ease-in-out"
              :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed }">
            <div class="p-6">
                @yield('content')
            </div>
            <x-footer-dashboard />
        </main>
    </div>

    @stack('scripts')

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `p-4 rounded-lg shadow-lg text-white transform transition-transform duration-300 ease-in-out translate-x-full`;

            switch(type) {
                case 'success':
                    toast.classList.add('bg-green-500');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500');
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-500');
                    break;
                default:
                    toast.classList.add('bg-blue-500');
            }

            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.getElementById('toast-container').appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }
    </script>

    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        .ts-control {
            border-radius: 0.5rem !important;
            border-color: #4b5563 !important; /* gray-600 */
        }
        .dark .ts-control {
            background-color: #374151 !important; /* slate-700 */
            border-color: #4b5563 !important; /* gray-600 */
            color: white;
        }
        .dark .ts-dropdown {
            background-color: #1e293b !important; /* slate-800 */
            border-color: #4b5563 !important; /* gray-600 */
        }
        .dark .ts-dropdown .option {
            color: #d1d5db; /* gray-300 */
        }
        .dark .ts-dropdown .active {
            background-color: #3b82f6 !important; /* blue-500 */
            color: white;
        }
    </style>
</body>
</html>