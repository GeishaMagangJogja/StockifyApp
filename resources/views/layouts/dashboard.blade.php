<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Stockify') }}</title>
    
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

    <style>
        /* Mencegah FOUC pada elemen-elemen Alpine.js */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-dark-secondary">
    
    <div x-data="{ 
            sidebarOpen: false, 
            darkMode: localStorage.getItem('darkMode') === 'true',
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode);
                document.documentElement.classList.toggle('dark', this.darkMode);
            }
         }" 
         class="flex h-screen">

        {{-- Komponen Navbar dipanggil di sini --}}
        <x-navbar-dashboard />

        {{-- Sidebar HANYA untuk user yang sudah login --}}
        @auth
            <!-- Sidebar Backdrop untuk mobile -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-10 bg-black opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-20 w-64 bg-white dark:bg-dark-primary shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 pt-16"
                   :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
                
                <div class="h-full px-3 pb-4 overflow-y-auto">
                    <!-- User Info -->
                    <div class="px-3 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-11 w-11 rounded-full object-cover ring-2 ring-blue-400" 
                                     src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff' }}" 
                                     alt="{{ Auth::user()->name }}">
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                    </div>
    
                    <!-- Navigation -->
                    <nav class="flex-1 mt-2 px-1">
                        @if (auth()->user()->role === 'Admin')
                            <x-sidebar.admin-sidebar />
                        @elseif (auth()->user()->role === 'Manajer Gudang')
                            <x-sidebar.manajergudang-sidebar />
                        @endif
                    </nav>
                </div>
                
                <!-- Laravel Logo -->
                <div class="absolute bottom-4 left-4">
                    <svg class="h-8 w-auto text-red-500" viewBox="0 0 119 125" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.5 60.55V125H59.25V60.55H0V0H59.25V60.55H118.5Z" fill="currentColor"/></svg>
                </div>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto pt-16 @auth lg:pl-64 @endauth">
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

    