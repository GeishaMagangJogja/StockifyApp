<div class="space-y-2">
    <x-sidebar.link :href="route('manajergudang.dashboard')" :active="request()->routeIs('manajergudang.dashboard')" icon="fas fa-home">
        Dashboard
    </x-sidebar.link>

    <x-sidebar.link :href="route('manajergudang.products.index')" :active="request()->routeIs('manajergudang.products.*')" icon="fas fa-box-archive">
        Daftar Produk
    </x-sidebar.link>

    <x-sidebar.dropdown :active="request()->routeIs('manajergudang.stock.*')" icon="fas fa-clipboard-list">
        <x-slot name="trigger">Stok</x-slot>
        <x-sidebar.sublink :href="route('manajergudang.stock.in')" :active="request()->routeIs('manajergudang.stock.in')">Barang Masuk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('manajergudang.stock.out')" :active="request()->routeIs('manajergudang.stock.out')">Barang Keluar</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('manajergudang.stock.opname')" :active="request()->routeIs('manajergudang.stock.opname')">Stock Opname</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.link :href="route('manajergudang.suppliers.index')" :active="request()->routeIs('manajergudang.suppliers.*')" icon="fas fa-truck">
        Supplier
    </x-sidebar.link>

    <x-sidebar.dropdown :active="request()->routeIs('manajergudang.reports.*')" icon="fas fa-chart-pie">
        <x-slot name="trigger">Laporan</x-slot>
        <x-sidebar.sublink :href="route('manajergudang.reports.stock')" :active="request()->routeIs('manajergudang.reports.stock')">Laporan Stok</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('manajergudang.reports.transactions')" :active="request()->routeIs('manajergudang.reports.transactions')">Laporan Transaksi</x-sidebar.sublink>
    </x-sidebar.dropdown>
</div>