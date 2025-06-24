@extends('layouts.dashboard') {{-- Ganti dengan nama layout utama Anda --}}

@section('title', 'Admin Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card 1: Jumlah Produk --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    {{-- Ganti dengan ikon SVG dari Heroicons atau Flowbite --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Produk
                    </p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                        {{ $totalProducts }} {{-- Variabel dari Controller --}}
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card 2: Jumlah Supplier --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            {{-- Mirip dengan Card 1, ganti ikon dan teks --}}
            ...
        </div>

        {{-- Card 3 & 4 untuk Transaksi Masuk/Keluar --}}
        ...
    </div>

    {{-- Baris Kedua: Grafik dan Stok Kritis --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        {{-- Card Grafik (Lebar 2 Kolom) --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow lg:col-span-2 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Statistik Stok</h5>
            {{-- Di sini Anda akan me-render chart dengan library seperti ApexCharts.js --}}
            <div id="stock-chart"></div>
        </div>

        {{-- Card Stok Kritis --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Stok Akan Habis</h5>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($lowStockProducts as $product) {{-- Variabel dari Controller --}}
                    <div class="py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                        <p class="text-sm text-red-600 dark:text-red-500">Sisa: {{ $product->current_stock }} (Min: {{ $product->minimum_stock }})</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Semua stok aman.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Script untuk library chart --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Ambil data dari controller (misal: dikirim sebagai JSON)
    const chartData = {!! json_encode($chartData) !!};

    const options = {
        series: [{
            name: 'Stok',
            data: chartData.data
        }],
        chart: { type: 'bar', height: 350 },
        xaxis: { categories: chartData.categories }
    };

    const chart = new ApexCharts(document.querySelector("#stock-chart"), options);
    chart.render();
</script>
@endpush