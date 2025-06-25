@extends('layouts.dashboard')

@section('title', 'Dashboard Manajer Gudang')

@section('content')
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card: Total Produk --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    {{-- Ganti dengan ikon yang sesuai --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Total Supplier --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                 <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.184-1.268-.5-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.184-1.268.5-1.857M12 12a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalSuppliers }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Barang Masuk Hari Ini --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
             <div class="flex items-center">
                <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Barang Masuk Hari Ini</p>
                    {{-- [REVISI] Nama variabel disesuaikan dengan controller --}}
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $incomingTodayCount }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Barang Keluar Hari Ini --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3m13.5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Barang Keluar Hari Ini</p>
                    {{-- [REVISI] Nama variabel disesuaikan dengan controller --}}
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $outgoingTodayCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik & Stok Menipis --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        {{-- Card: Stok Menipis --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Stok Akan Habis</h5>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($lowStockProducts as $product)
                    <div class="py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                        {{-- [REVISI] Menggunakan current_stock yang sudah dihitung di controller --}}
                        <p class="text-sm text-red-600 dark:text-red-500">Sisa: {{ $product->current_stock }} (Min: {{ $product->minimum_stock }})</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Semua stok dalam batas aman.</p>
                @endforelse
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
