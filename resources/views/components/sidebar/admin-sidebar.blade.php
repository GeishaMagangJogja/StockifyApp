<div class="space-y-2">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
        <i class="fas fa-tachometer-alt fa-fw w-5 mr-3"></i>
        <span class="font-medium">Dashboard</span>
    </a>

    <!-- Kelola User Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-users fa-fw w-5 mr-3"></i>
                <span class="font-medium">Kelola User</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.users.index') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Daftar User
            </a>
            <a href="{{ route('admin.users.create') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.users.create') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Tambah User
            </a>
        </div>
    </div>
    
    <!-- Kelola Produk Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('admin.products.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-box fa-fw w-5 mr-3"></i>
                <span class="font-medium">Kelola Produk</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.products.index') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Daftar Produk
            </a>
            <a href="{{ route('admin.products.create') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.products.create') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Tambah Produk
            </a>
        </div>
    </div>

    <!-- Kategori (Link Tunggal) -->
    <div x-data="{ open: {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-tags fa-fw w-5 mr-3"></i>
                <span class="font-medium">Kategori</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.categories.index') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Daftar Kategori
            </a>
            <a href="{{ route('admin.categories.create') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.categories.create') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Tambah Kategori
            </a>
        </div>
    </div>
    
    <!-- Supplier (Link Tunggal) -->
    <div x-data="{ open: {{ request()->routeIs('admin.suppliers.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-truck fa-fw w-5 mr-3"></i>
                <span class="font-medium">Supplier</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('admin.suppliers.index') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.suppliers.index') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Daftar Supplier
            </a>
            <a href="{{ route('admin.suppliers.create') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.suppliers.create') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Tambah Supplier
            </a>
        </div>
    </div>

    <!-- Laporan Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-chart-pie fa-fw w-5 mr-3"></i>
                <span class="font-medium">Laporan</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('admin.reports.stock') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.stock') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Laporan Stok
            </a>
            <a href="{{ route('admin.reports.transactions') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.transactions') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Laporan Transaksi
            </a>
        </div>
    </div>

    <!-- Pengaturan -->
    <a href="{{ route('admin.settings') }}"
       class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
        <i class="fas fa-cog fa-fw w-5 mr-3"></i>
        <span class="font-medium">Setting</span>
    </a>
</div>
