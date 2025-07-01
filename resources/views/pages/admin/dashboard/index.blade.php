@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    {{-- Header Halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Berikut adalah ringkasan aktivitas sistem hari ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.reports.stock') }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700">
                Lihat Laporan
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700">
                <i class="mr-1 fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))<div class="p-4 mb-6 text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg">{{ session('error') }}</div>@endif

    {{-- Kartu Statistik Utama --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-br from-green-500 to-green-600"><div class="flex items-start justify-between"><div><p class="text-sm font-medium opacity-80">Total Produk</p><p class="text-3xl font-bold">{{ number_format($totalProducts ?? 0) }}</p></div><i class="text-4xl opacity-50 fas fa-box"></i></div></div>
        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-br from-blue-500 to-blue-600"><div class="flex items-start justify-between"><div><p class="text-sm font-medium opacity-80">Total Supplier</p><p class="text-3xl font-bold">{{ number_format($totalSuppliers ?? 0) }}</p></div><i class="text-4xl opacity-50 fas fa-truck"></i></div></div>
        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-br from-indigo-500 to-indigo-600"><div class="flex items-start justify-between"><div><p class="text-sm font-medium opacity-80">Total Kategori</p><p class="text-3xl font-bold">{{ number_format($totalCategories ?? 0) }}</p></div><i class="text-4xl opacity-50 fas fa-tags"></i></div></div>
        <div class="p-6 text-white rounded-lg shadow-lg bg-gradient-to-br from-purple-500 to-purple-600"><div class="flex items-start justify-between"><div><p class="text-sm font-medium opacity-80">Total Pengguna</p><p class="text-3xl font-bold">{{ number_format($totalUsers ?? 0) }}</p></div><i class="text-4xl opacity-50 fas fa-users"></i></div></div>
    </div>

    {{-- PERBAIKAN UTAMA DI SINI: Menggabungkan Grafik dan Aktivitas --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Kolom Kiri: Gabungan Grafik dan Stok Rendah --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Kartu Grafik --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Aktivitas Transaksi (7 Hari Terakhir)</h5>
                <div id="main-chart"></div>
            </div>

            {{-- KARTU BARU: STOK AKAN HABIS (DIPINDAHKAN KE SINI) --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Stok Akan Habis</h5>
                <div class="space-y-3">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $product->min_stock }} {{ $product->unit }}</p>
                            </div>
                            <span class="text-base font-bold text-red-500">{{ $product->current_stock }}</span>
                        </div>
                    @empty
                        <p class="py-4 text-sm text-center text-gray-500 dark:text-gray-400">Semua stok dalam batas aman.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Aktivitas Terbaru --}}
        <div class="space-y-6">
            {{-- Kartu: Transaksi Terbaru --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Transaksi Terbaru</h5>
                <div class="space-y-4">
                    @forelse ($recentTransactions as $transaction)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $transaction->type == 'Masuk' ? 'bg-green-100 dark:bg-green-500/20' : 'bg-red-100 dark:bg-red-500/20' }}">
                            <i class="fas {{ $transaction->type == 'Masuk' ? 'fa-arrow-down text-green-500' : 'fa-arrow-up text-red-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $transaction->product->name ?? 'Produk Dihapus' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d M Y') }}</p>
                        </div>
                        <div class="text-sm font-semibold {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type == 'Masuk' ? '+' : '-' }}{{ $transaction->quantity }}
                        </div>
                    </div>
                    @empty
                    <p class="py-4 text-sm text-center text-gray-500 dark:text-gray-400">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kartu: Pengguna Baru --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Pengguna Baru</h5>
                <div class="space-y-4">
                    @forelse ($recentUsers as $user)
                    <div class="flex items-center space-x-4">
                        <img class="object-cover w-10 h-10 rounded-full" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="{{ $user->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->role }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="py-4 text-sm text-center text-gray-500 dark:text-gray-400">Tidak ada pengguna baru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = {!! json_encode($chartData) !!};
            const options = {
                series: [{ name: 'Barang Masuk', data: chartData.incoming, color: '#22c55e' }, { name: 'Barang Keluar', data: chartData.outgoing, color: '#ef4444' }],
                chart: { type: 'area', height: 350, toolbar: { show: false }, background: 'transparent' },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: { categories: chartData.categories, labels: { style: { colors: '#6B7280' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { colors: '#6B7280' } } },
                grid: { borderColor: '#e7e7e720', strokeDashArray: 4 },
                tooltip: { y: { formatter: (val) => `${val} unit` }, theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                legend: { position: 'top', horizontalAlign: 'right', labels: { colors: '#6B7280' } }
            };
            const chart = new ApexCharts(document.querySelector("#main-chart"), options);
            chart.render();
        });
    </script>
@endpush
