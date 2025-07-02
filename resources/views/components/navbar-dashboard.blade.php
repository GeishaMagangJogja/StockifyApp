<nav class="fixed top-0 z-30 w-full bg-white border-b border-slate-200 dark:bg-dark-primary dark:border-slate-700">
    <div class="px-4 py-2.5">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                @auth
                {{-- Tombol Hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 rounded-lg cursor-pointer lg:hidden dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-700">
                    <i class="text-xl fas fa-bars"></i>
                </button>
                @endauth
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center ml-2 md:mr-24">
                     <span class="text-xl font-bold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
                </a>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3">
                @auth
                @php $userRole = auth()->user()->role; @endphp

                {{-- Dark Mode Toggle --}}
                <button @click="toggleDarkMode" type="button" class="p-2 text-gray-500 rounded-lg hover:bg-slate-100 dark:text-gray-400 dark:hover:bg-slate-700 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-700">
                    <span class="sr-only">Toggle dark mode</span>
                    <i class="fas fa-sun" x-show="!darkMode" x-cloak></i>
                    <i class="fas fa-moon" x-show="darkMode" x-cloak></i>
                </button>

                {{-- Apps Dropdown (Pintasan Cepat) --}}
                <div x-data="{ open: false }" class="relative hidden sm:block">
                    <button @click="open = !open" class="p-2 text-gray-500 rounded-lg hover:bg-slate-100 dark:text-gray-400 dark:hover:bg-slate-700 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-700">
                        <span class="sr-only">View apps</span>
                        <i class="text-lg fas fa-th-large fa-fw"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 w-64 mt-2 origin-top-right bg-white border shadow-xl rounded-xl dark:bg-dark-primary dark:border-slate-700 ring-1 ring-black ring-opacity-5">
                        <div class="px-4 py-3 text-sm font-medium text-gray-800 bg-slate-50 dark:bg-slate-800 dark:text-gray-200 rounded-t-xl">
                            Pintasan Cepat
                        </div>
                        <div class="grid grid-cols-3 gap-2 p-3">
                            @if($userRole === 'Admin')
                                <x-navbar.shortcut-link :href="route('admin.users.create')" icon="fas fa-user-plus" color="purple">User Baru</x-navbar.shortcut-link>
                                <x-navbar.shortcut-link :href="route('admin.products.create')" icon="fas fa-box" color="green">Produk Baru</x-navbar.shortcut-link>
                                <x-navbar.shortcut-link :href="route('admin.suppliers.create')" icon="fas fa-truck" color="blue">Supplier</x-navbar.shortcut-link>
                            @elseif($userRole === 'Manajer Gudang')
                                <x-navbar.shortcut-link :href="route('manajergudang.stock.in')" icon="fas fa-arrow-down" color="green">Barang Masuk</x-navbar.shortcut-link>
                                <x-navbar.shortcut-link :href="route('manajergudang.stock.out')" icon="fas fa-arrow-up" color="red">Barang Keluar</x-navbar.shortcut-link>
                                <x-navbar.shortcut-link :href="route('manajergudang.stock.opname')" icon="fas fa-tasks" color="yellow">Stock Opname</x-navbar.shortcut-link>
                            @elseif($userRole === 'Staff Gudang')
                                <x-navbar.shortcut-link :href="route('staff.stock.incoming.list')" icon="fas fa-dolly" color="sky">Tugas Masuk</x-navbar.shortcut-link>
                                <x-navbar.shortcut-link :href="route('staff.stock.outgoing.list')" icon="fas fa-truck-loading" color="orange">Tugas Keluar</x-navbar.shortcut-link>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-primary focus:ring-white/50">
                        <img class="object-cover rounded-full w-9 h-9"
                             src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff' }}"
                             alt="{{ Auth::user()->name }}">
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition duration-150 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white border divide-y rounded-lg shadow-xl dark:bg-dark-primary dark:border-slate-700 divide-slate-100 dark:divide-slate-700 ring-1 ring-black ring-opacity-5">
                        <div class="px-4 py-3">
                            <p class="text-sm font-semibold text-gray-800 truncate dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ match(Auth::user()->role) { 'Admin' => route('admin.profile'), 'Manajer Gudang' => route('manajergudang.profile'), 'Staff Gudang' => route('staff.profile'), default => '#' } }}" class="block w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-slate-700/50">Profil Saya</a>
                        </div>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center justify-between w-full px-4 py-2 text-sm text-left text-red-600 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700/50">
                                    <span>Logout</span>
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
