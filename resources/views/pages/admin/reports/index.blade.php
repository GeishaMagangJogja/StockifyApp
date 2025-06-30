@extends('layouts.dashboard')

@section('title', 'Pusat Laporan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pusat Laporan</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Pilih jenis laporan yang ingin Anda lihat.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Laporan Stok -->
    <a href="{{ route('admin.reports.stock') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 border dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full dark:bg-blue-900">
                <i class="fas fa-boxes text-blue-600 dark:text-blue-300 fa-lg"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Stok</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lihat stok produk dan jumlah transaksi.</p>
            </div>
        </div>
    </a>

    <!-- Card Laporan Transaksi -->
    <a href="{{ route('admin.reports.transactions') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 border dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full dark:bg-green-900">
                <i class="fas fa-exchange-alt text-green-600 dark:text-green-300 fa-lg"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Transaksi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Detail transaksi barang masuk dan keluar.</p>
            </div>
        </div>
    </a>

    <!-- Card Laporan Pengguna -->
    <a href="{{ route('admin.reports.users') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 border dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full dark:bg-purple-900">
                <i class="fas fa-users text-purple-600 dark:text-purple-300 fa-lg"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Pengguna</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lihat data semua pengguna terdaftar.</p>
            </div>
        </div>
    </a>

    <!-- Card Laporan Sistem -->
    <a href="{{ route('admin.reports.system') }}" class="block p-6 bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 dark:bg-gray-800 border dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-full dark:bg-orange-900">
                <i class="fas fa-server text-orange-600 dark:text-orange-300 fa-lg"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Sistem</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Statistik keseluruhan sistem.</p>
            </div>
        </div>
    </a>
</div>
@endsection