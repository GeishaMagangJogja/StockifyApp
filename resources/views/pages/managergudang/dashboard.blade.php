@extends('layouts.dashboard') {{-- Pastikan layout ini sudah sesuai dengan struktur kamu --}}

@section('title', 'Dashboard Manajer Gudang')

@section('content')
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Jumlah Produk --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5M14 5H10a2 2 0 00-2 2v2m8 0v-2a2 2 0 00-2-2h-4"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        {{-- Jumlah Supplier --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalSuppliers }}</p>
                </div>
            </div>
        </div>

        {{-- Barang Masuk Hari Ini --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Barang Masuk Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $todayIncoming }}</p>
                </div>
            </div>
        </div>

        {{-- Barang Keluar Hari Ini --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V12l-3-3"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Barang Keluar Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $todayOutgoing }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik & Stok Menipis --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        {{-- Grafik --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow lg:col-span-2 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Grafik Transaksi</h5>
            <div id="stock-chart"></div>
        </div>

        {{-- Stok Menipis --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Stok Akan Habis</h5>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($lowStockProducts as $product)
                    <div class="py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                        <p class="text-sm text-red-600 dark:text-red-500">Sisa: {{ $product->current_stock }} (Min: {{ $product->minimum_stock }})</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Semua stok dalam batas aman.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Chart library --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const chartData = {!! json_encode($chartData) !!};

        const options = {
            series: [{
                name: 'Jumlah',
                data: chartData.data
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            xaxis: {
                categories: chartData.categories
            }
        };

        const chart = new ApexCharts(document.querySelector("#stock-chart"), options);
        chart.render();
    </script>
@endpush
