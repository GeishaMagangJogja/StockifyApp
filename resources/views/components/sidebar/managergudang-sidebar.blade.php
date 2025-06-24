{{-- resources/views/components/sidebar/manajergudang-sidebar.blade.php --}}

<x-sidebar-dashboard>
    {{-- Dashboard --}}
    <x-sidebar-menu-dashboard 
        :routeName="'manajergudang.dashboard'" 
        title="Dashboard" 
        icon="home" 
    />

    {{-- Produk --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'manajergudang.products.*'" 
        title="Produk"
        icon="archive-box"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.products.index'" title="Daftar Produk"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.products.show'" title="Detail Produk"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Stok --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'manajergudang.stock.*'" 
        title="Stok"
        icon="clipboard-document-list"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.stock.in'" title="Barang Masuk"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.stock.out'" title="Barang Keluar"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.stock.opname'" title="Stock Opname"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Supplier --}}
    <x-sidebar-menu-dashboard 
        :routeName="'manajergudang.suppliers.index'" 
        title="Supplier"
        icon="truck"
    />

    {{-- Laporan --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'manajergudang.reports.*'" 
        title="Laporan"
        icon="chart-pie"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.reports.stock'" title="Laporan Stok"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'manajergudang.reports.transactions'" title="Laporan Transaksi"/>
    </x-sidebar-menu-dropdown-dashboard>
</x-sidebar-dashboard>