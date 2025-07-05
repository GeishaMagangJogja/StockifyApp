<div class="space-y-2">
    {{-- 1. Dashboard --}}
    <x-sidebar.link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')" icon="fas fa-clipboard-list">
        Dashboard Tugas
    </x-sidebar.link>

    {{-- 2. Manajemen Stok (Dropdown) - Berfungsi sebagai Riwayat Lengkap --}}
    <x-sidebar.dropdown :active="request()->routeIs('staff.stock.*')" icon="fas fa-boxes-stacked">
        <x-slot name="trigger">Manajemen Stok</x-slot>
        <x-sidebar.sublink :href="route('staff.stock.incoming.list')" :active="request()->routeIs('staff.stock.incoming.*')">Daftar Barang Masuk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('staff.stock.outgoing.list')" :active="request()->routeIs('staff.stock.outgoing.*')">Daftar Barang Keluar</x-sidebar.sublink>
    </x-sidebar.dropdown>

    {{-- ======================================================= --}}
    {{-- == PERBAIKAN UTAMA: MENU TUGAS MENJADI SATU LINK == --}}
    {{-- ======================================================= --}}
    
    {{-- 3. Manajemen Tugas (Link Langsung ke Pusat Tugas) --}}
    <x-sidebar.link :href="route('staff.tasks.index')" :active="request()->routeIs('staff.tasks.*')" icon="fas fa-list-check">
        Manajemen Tugas
    </x-sidebar.link>
    
    {{-- ======================================================= --}}

    {{-- 4. Laporan (Dropdown) --}}
    <x-sidebar.dropdown :active="request()->routeIs('staff.reports.*')" icon="fas fa-chart-line">
        <x-slot name="trigger">Laporan</x-slot>
        <x-sidebar.sublink :href="route('staff.reports.incoming')" :active="request()->routeIs('staff.reports.incoming')">Riwayat Stok Masuk</x-sidebar.sublink>
        <x-sidebar.sublink :href="route('staff.reports.outgoing')" :active="request()->routeIs('staff.reports.outgoing')">Riwayat Stok Keluar</x-sidebar.sublink>
    </x-sidebar.dropdown>
</div>