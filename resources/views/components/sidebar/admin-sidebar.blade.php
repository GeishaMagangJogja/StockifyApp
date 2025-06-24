{{-- resources/views/components/sidebar/admin-sidebar.blade.php --}}

<x-sidebar-dashboard>
    {{-- Item Menu Tunggal untuk Dashboard --}}
    <x-sidebar-menu-dashboard 
        :routeName="'admin.dashboard'" 
        title="Dashboard" 
        icon="home" {{-- Asumsi komponen Anda bisa menerima ikon --}}
    />

    {{-- Dropdown untuk Manajemen Produk --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'admin.products.*|admin.categories.*|admin.attributes.*'" {{-- Agar dropdown aktif di halaman-halaman ini --}}
        title="Produk"
        icon="archive-box"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.products.index'" title="Daftar Produk"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.categories.index'" title="Kategori Produk"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.attributes.index'" title="Atribut Produk"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Dropdown untuk Manajemen Stok --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'admin.stock.*'" 
        title="Stok"
        icon="clipboard-document-list"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.stock.history'" title="Riwayat Transaksi"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.stock.opname'" title="Stock Opname"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Item Menu Tunggal untuk Supplier --}}
    <x-sidebar-menu-dashboard 
        :routeName="'admin.suppliers.index'" 
        title="Supplier"
        icon="truck"
    />

    {{-- Item Menu Tunggal untuk Pengguna --}}
    <x-sidebar-menu-dashboard 
        :routeName="'admin.users.index'" 
        title="Pengguna"
        icon="users"
    />
    
    {{-- Dropdown untuk Laporan --}}
    <x-sidebar-menu-dropdown-dashboard 
        :routeName="'admin.reports.*'" 
        title="Laporan"
        icon="chart-pie"
    >
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.reports.stock'" title="Laporan Stok"/>
        <x-sidebar-menu-dropdown-item-dashboard :routeName="'admin.reports.transactions'" title="Laporan Transaksi"/>
    </x-sidebar-menu-dropdown-dashboard>

    {{-- Item Menu Tunggal untuk Pengaturan --}}
    <x-sidebar-menu-dashboard 
        :routeName="'admin.settings.index'" 
        title="Pengaturan"
        icon="cog-6-tooth"
    />

</x-sidebar-dashboard>