<nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 dark:bg-dark-primary dark:border-slate-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                @auth
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                @endauth
                <a href="{{ url('/') }}" class="flex ml-2 md:mr-24">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stockify</h1>
                </a>
            </div>
        
            <div class="flex items-center space-x-4">
                @auth
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode" type="button" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                    <i class="fas fa-sun" x-show="!darkMode" x-cloak></i>
                    <i class="fas fa-moon" x-show="darkMode" x-cloak></i>
                </button>

                <!-- Apps Dropdown (Dinamis Berdasarkan Peran) -->
                <div x-data="{ open: false }" class="relative hidden sm:block">
                    <button @click="open = !open" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                        <i class="fas fa-th-large fa-fw text-lg"></i>
                        <span class="sr-only">View apps</span>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-72 bg-white dark:bg-dark-primary rounded-md shadow-lg border border-gray-200 dark:border-slate-700 z-50">
                        <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-slate-700 dark:text-gray-400">
                            Pintasan Cepat
                        </div>
                        <div class="grid grid-cols-3 gap-4 p-4">
                            {{-- PINTASAN UNTUK ADMIN --}}
                            @if(auth()->user()->role === 'Admin')
                                <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-purple-100 rounded-full dark:bg-purple-500/30">
                                        <i class="fas fa-user-plus text-xl text-purple-600 dark:text-purple-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">User Baru</p>
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-green-100 rounded-full dark:bg-green-500/30">
                                        <i class="fas fa-box text-xl text-green-600 dark:text-green-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Produk Baru</p>
                                </a>
                                <a href="{{ route('admin.suppliers.create') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-blue-100 rounded-full dark:bg-blue-500/30">
                                        <i class="fas fa-truck text-xl text-blue-600 dark:text-blue-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Supplier Baru</p>
                                </a>
                            {{-- PINTASAN UNTUK MANAJER GUDANG --}}
                            @elseif(auth()->user()->role === 'Manajer Gudang')
                                <a href="{{ route('manajergudang.stock.in') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-green-100 rounded-full dark:bg-green-500/30">
                                        <i class="fas fa-arrow-down text-xl text-green-600 dark:text-green-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Barang Masuk</p>
                                </a>
                                <a href="{{ route('manajergudang.stock.out') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-red-100 rounded-full dark:bg-red-500/30">
                                        <i class="fas fa-arrow-up text-xl text-red-600 dark:text-red-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Barang Keluar</p>
                                </a>
                                <a href="{{ route('manajergudang.stock.opname') }}" class="flex flex-col items-center p-2 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <div class="flex items-center justify-center w-12 h-12 mb-2 bg-yellow-100 rounded-full dark:bg-yellow-500/30">
                                        <i class="fas fa-tasks text-xl text-yellow-600 dark:text-yellow-300"></i>
                                    </div>
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Stock Opname</p>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Notifikasi (Placeholder) --}}
                <div x-data="{ open: false }" class="relative hidden sm:block">
                    <button @click="open = !open" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700">
                        <span class="sr-only">View notifications</span>
                        <i class="fas fa-bell fa-fw"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white dark:bg-dark-primary rounded-md shadow-lg border dark:border-slate-700 z-50">
                        <div class="block px-4 py-2 font-medium text-center text-gray-700 bg-gray-50 dark:bg-slate-700 dark:text-gray-400">Notifikasi</div>
                        <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi baru.</div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                        <img class="w-9 h-9 rounded-full object-cover" 
                        src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff' }}" 
                        alt="{{ Auth::user()->name }}">
                        <i class="fas fa-chevron-down text-xs transform" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-primary rounded-md shadow-lg border dark:border-slate-700 z-50">
                        <div class="px-4 py-3 border-b dark:border-slate-700">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ Auth::user()->role === 'Admin' ? route('admin.profile') : route('manajergudang.profile') }}" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                    Profil
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Logout</button>
                                </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>