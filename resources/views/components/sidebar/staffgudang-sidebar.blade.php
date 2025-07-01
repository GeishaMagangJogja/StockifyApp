<div class="space-y-2">
    <x-sidebar.link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')" icon="fas fa-clipboard-list">
        Dashboard Tugas
    </x-sidebar.link>

    <x-sidebar.dropdown :active="request()->routeIs('staff.stock.*')" icon="fas fa-boxes-stacked">
        <x-slot name="trigger">Manajemen Stok</x-slot>
        <x-sidebar.sublink :href="route('staff.stock.incoming.list')" :active="request()->routeIs('staff.stock.incoming.*')">Daftar Barang Masuk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('staff.stock.outgoing.list')" :active="request()->routeIs('staff.stock.outgoing.*')">Daftar Barang Keluar</x-sidebar.sublink>
    </x-sidebar.dropdown>

    <x-sidebar.dropdown :active="request()->routeIs('staff.reports.*')" icon="fas fa-chart-line">
        <x-slot name="trigger">Laporan</x-slot>
        <x-sidebar.sublink :href="route('staff.reports.incoming')" :active="request()->routeIs('staff.reports.incoming')">Riwayat Stok Masuk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('staff.reports.outgoing')" :active="request()->routeIs('staff.reports.outgoing')">Riwayat Stok Keluar</x-sidebar.sublink>
    </x-sidebar.dropdown>
</div>