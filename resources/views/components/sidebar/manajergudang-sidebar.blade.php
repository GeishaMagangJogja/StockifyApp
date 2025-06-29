<div class="space-y-2">
    <!-- Dashboard -->
    <a href="{{ route('manajergudang.dashboard') }}"
       class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 
              {{ request()->routeIs('manajergudang.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
        <i class="fas fa-home fa-fw w-5 mr-3"></i>
        <span class="font-medium">Dashboard</span>
    </a>

    <!-- Daftar Produk (Link Tunggal) -->
    <a href="{{ route('manajergudang.products.index') }}"
       class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 
              {{ request()->routeIs('manajergudang.products.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
        <i class="fas fa-box-archive fa-fw w-5 mr-3"></i>
        <span class="font-medium">Daftar Produk</span>
    </a>

    <!-- Stok Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('manajergudang.stock.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200
                       {{ request()->routeIs('manajergudang.stock.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-clipboard-list fa-fw w-5 mr-3"></i>
                <span class="font-medium">Stok</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('manajergudang.stock.in') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('manajergudang.stock.in') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Barang Masuk
            </a>
            <a href="{{ route('manajergudang.stock.out') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('manajergudang.stock.out') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Barang Keluar
            </a>
            <a href="{{ route('manajergudang.stock.opname') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('manajergudang.stock.opname') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Stock Opname
            </a>
        </div>
    </div>

    <!-- Supplier (Link Tunggal) -->
    <a href="{{ route('manajergudang.suppliers.index') }}"
       class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 
              {{ request()->routeIs('manajergudang.suppliers.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
        <i class="fas fa-truck fa-fw w-5 mr-3"></i>
        <span class="font-medium">Supplier</span>
    </a>

    <!-- Laporan Dropdown -->
    <div x-data="{ open: {{ request()->routeIs('manajergudang.reports.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg transition-colors duration-200 
                       {{ request()->routeIs('manajergudang.reports.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white' }}">
            <span class="flex items-center">
                <i class="fas fa-chart-pie fa-fw w-5 mr-3"></i>
                <span class="font-medium">Laporan</span>
            </span>
            <i class="fas fa-chevron-down text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
        </button>
        <div x-show="open" x-collapse class="pl-6 mt-1 space-y-1">
            <a href="{{ route('manajergudang.reports.stock') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('manajergudang.reports.stock') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Laporan Stok
            </a>
            <a href="{{ route('manajergudang.reports.transactions') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('manajergudang.reports.transactions') ? 'text-blue-600 dark:text-white font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
               Laporan Transaksi
            </a>
        </div>
    </div>
</div>