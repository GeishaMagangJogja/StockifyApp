<nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 dark:bg-dark-primary dark:border-slate-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
          
                @auth
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-slate-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-slate-700 dark:text-gray-400 dark:hover:bg-slate-700 dark:hover:text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                @endauth

                <a href="{{ url('/') }}" class="flex ml-2 md:mr-24">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Stockify</h1>
                </a>
            </div>
        
            <div class="flex items-center space-x-4">
                @auth
                <!-- Dark Mode Toggle dengan Alpine.js -->
                <button @click="toggleDarkMode" type="button" class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-slate-700">
                    <span class="sr-only">Toggle dark mode</span>
                    <i class="fas fa-sun" x-show="!darkMode" x-cloak></i>
                    <i class="fas fa-moon" x-show="darkMode" x-cloak></i>
                </button>

                <!-- Apps Dropdown -->
                <div x-data="{ open: false }" class="relative hidden sm:block">
                    <button @click="open = !open" class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700">
                        <span class="sr-only">View apps</span>
                        <i class="fas fa-th-large fa-fw"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white dark:bg-dark-primary rounded-md shadow-lg border border-gray-200 dark:border-slate-700 z-50">
                        <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-slate-700 dark:text-gray-400">
                            Apps
                        </div>
                        <div class="grid grid-cols-3 gap-4 p-4">
                            <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                <i class="fas fa-shopping-cart text-xl text-gray-500 dark:text-gray-400"></i>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-2">Sales</div>
                            </a>
                            <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                <i class="fas fa-users text-xl text-gray-500 dark:text-gray-400"></i>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-2">Users</div>
                            </a>
                            <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                <i class="fas fa-box text-xl text-gray-500 dark:text-gray-400"></i>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-2">Products</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                        <img class="w-9 h-9 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" alt="user photo">
                        <span class="hidden md:inline font-medium">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-primary rounded-md shadow-lg border border-gray-200 dark:border-slate-700 z-50">
                        <div class="py-1">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <i class="fas fa-sign-out-alt fa-fw mr-2"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
          
                @guest
                <div class="flex items-center ml-3 space-x-2">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-700">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Sign Up
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </div>
</nav>