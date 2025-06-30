@extends('layouts.dashboard')

@section('title', 'Dashboard Manajer Gudang')

@section('content')
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Baris Pertama: Kartu Statistik --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg text-white"><div class="flex justify-between items-start"><div><p class="text-sm font-medium opacity-80">Total Produk</p><p class="text-3xl font-bold">{{ number_format($totalProducts) }}</p></div><i class="fas fa-box text-4xl opacity-50"></i></div></div>
        <div class="p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg text-white"><div class="flex justify-between items-start"><div><p class="text-sm font-medium opacity-80">Total Supplier</p><p class="text-3xl font-bold">{{ number_format($totalSuppliers) }}</p></div><i class="fas fa-truck text-4xl opacity-50"></i></div></div>
        <div class="p-6 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg text-white"><div class="flex justify-between items-start"><div><p class="text-sm font-medium opacity-80">Masuk Hari Ini</p><p class="text-3xl font-bold">{{ number_format($incomingTodayCount) }}</p></div><i class="fas fa-arrow-circle-down text-4xl opacity-50"></i></div></div>
        <div class="p-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg text-white"><div class="flex justify-between items-start"><div><p class="text-sm font-medium opacity-80">Keluar Hari Ini</p><p class="text-3xl font-bold">{{ number_format($outgoingTodayCount) }}</p></div><i class="fas fa-arrow-circle-up text-4xl opacity-50"></i></div></div>
    </div>

    {{-- Grafik & Aktivitas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Grafik dan Stok Rendah --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Aktivitas Transaksi (7 Hari Terakhir)</h5>
                <div id="main-chart"></div>
            </div>
             <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Stok Akan Habis</h5>
                <div class="space-y-3">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $product->min_stock }} {{ $product->unit }}</p>
                            </div>
                            <span class="text-base font-bold text-red-500">{{ $product->current_stock }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Semua stok dalam batas aman.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Aktivitas Terbaru --}}
        <div class="space-y-6">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Transaksi Terbaru</h5>
                <div class="space-y-4">
                    @forelse ($recentTransactions as $transaction)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $transaction->type == 'Masuk' ? 'bg-green-100 dark:bg-green-500/20' : 'bg-red-100 dark:bg-red-500/20' }}">
                            <i class="fas {{ $transaction->type == 'Masuk' ? 'fa-arrow-down text-green-500' : 'fa-arrow-up text-red-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $transaction->product->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $transaction->date->format('d M Y') }}
                                @if($transaction->notes)
                                    <span class="italic">- "{{ Str::limit($transaction->notes, 20) }}"</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-sm font-semibold {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type == 'Masuk' ? '+' : '-' }}{{ $transaction->quantity }}
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>

            {{-- KARTU BARU: SUPPLIER TERBARU --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Supplier Terbaru</h5>
                <div class="space-y-4">
                    @forelse ($recentSuppliers as $supplier)
                    <div class="flex items-center space-x-4">
                        <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=1e293b&color=fff" alt="{{ $supplier->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $supplier->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $supplier->email }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Belum ada supplier.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memuat library ApexCharts dari CDN --}}
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
                grid: { borderColor: '#e7e7e720' },
                tooltip: { y: { formatter: (val) => `${val} unit` }, theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                legend: { labels: { colors: '#6B7280' } }
            };
            const chart = new ApexCharts(document.querySelector("#main-chart"), options);
            chart.render();
        });
    </script>
@endpush
