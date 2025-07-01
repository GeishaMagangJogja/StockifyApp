<div class="space-y-2">
    <x-sidebar.link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="fas fa-tachometer-alt">
        Dashboard
    </x-sidebar.link>

    <x-sidebar.dropdown :active="request()->routeIs('admin.users.*')" icon="fas fa-users">
        <x-slot name="trigger">Kelola User</x-slot>
        <x-sidebar.sublink :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index', 'admin.users.edit', 'admin.users.show')">Daftar User</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('admin.users.create')" :active="request()->routeIs('admin.users.create')">Tambah User</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.dropdown :active="request()->routeIs('admin.products.*')" icon="fas fa-box-open">
        <x-slot name="trigger">Kelola Produk</x-slot>
        <x-sidebar.sublink :href="route('admin.products.index')" :active="request()->routeIs('admin.products.index', 'admin.products.edit', 'admin.products.show')">Daftar Produk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('admin.products.create')" :active="request()->routeIs('admin.products.create')">Tambah Produk</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.dropdown :active="request()->routeIs('admin.categories.*')" icon="fas fa-tags">
        <x-slot name="trigger">Kategori</x-slot>
        <x-sidebar.sublink :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index', 'admin.categories.edit', 'admin.categories.show')">Daftar Kategori</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('admin.categories.create')" :active="request()->routeIs('admin.categories.create')">Tambah Kategori</x-sidebar.sublink>
    </x-sidebar.dropdown>
    
    <x-sidebar.dropdown :active="request()->routeIs('admin.suppliers.*')" icon="fas fa-truck">
        <x-slot name="trigger">Supplier</x-slot>
        <x-sidebar.sublink :href="route('admin.suppliers.index')" :active="request()->routeIs('admin.suppliers.index', 'admin.suppliers.edit', 'admin.suppliers.show')">Daftar Supplier</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('admin.suppliers.create')" :active="request()->routeIs('admin.suppliers.create')">Tambah Supplier</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.dropdown :active="request()->routeIs('admin.reports.*')" icon="fas fa-chart-pie">
        <x-slot name="trigger">Laporan</x-slot>
        <x-sidebar.sublink :href="route('admin.reports.stock')" :active="request()->routeIs('admin.reports.stock')">Laporan Stok</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('admin.reports.transactions')" :active="request()->routeIs('admin.reports.transactions')">Laporan Transaksi</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" icon="fas fa-cog">
        Setting
    </x-sidebar.link>
</div>