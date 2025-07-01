@extends('layouts.dashboard')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('manajergudang.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 transition-colors hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        <a href="{{ route('manajergudang.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 transition-colors hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Produk</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Detail Produk</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Produk: {{ $product->name }}</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Kolom Kiri: Informasi dan Riwayat -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Kartu Informasi Produk -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md dark:bg-slate-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Produk</p><p class="mt-1 text-gray-900 dark:text-white">{{ $product->name }}</p></div>
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU</p><p class="mt-1 text-gray-900 dark:text-white">{{ $product->sku }}</p></div>
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</p><p class="mt-1 text-gray-900 dark:text-white">{{ $product->category->name ?? '-' }}</p></div>
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</p><p class="mt-1 text-gray-900 dark:text-white">{{ $product->supplier->name ?? '-' }}</p></div>
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</p><p class="mt-1 text-gray-900 dark:text-white">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p></div>
                        <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</p><p class="mt-1 text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p></div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                        <p class="mt-1 text-gray-900 dark:text-white prose dark:prose-invert max-w-none">{{ $product->description ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Kartu Riwayat Stok Terakhir -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md dark:bg-slate-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Transaksi Terakhir</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Tanggal & User</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Jenis</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($product->stockTransactions->sortByDesc('date')->take(10) as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><p class="font-medium text-gray-800 dark:text-white">{{ $transaction->date->format('d M Y, H:i') }}</p><p class="text-xs text-gray-500 dark:text-gray-400">oleh {{ $transaction->user->name ?? 'Sistem' }}</p></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->type == 'Masuk')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-300"><i class="mr-1.5 fas fa-arrow-down"></i> Masuk</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900/30 dark:text-red-300"><i class="mr-1.5 fas fa-arrow-up"></i> Keluar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-right whitespace-nowrap {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type == 'Masuk' ? '+' : '-' }}{{ number_format($transaction->quantity) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Gambar dan Status Stok -->
        <div class="space-y-6">
            <!-- Kartu Gambar Produk -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md dark:bg-slate-800">
                <div class="p-6">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-full rounded-md">
                    @else
                        <div class="flex items-center justify-center w-full h-48 bg-gray-100 rounded-md dark:bg-slate-700">
                            <i class="text-4xl text-gray-400 fas fa-image dark:text-slate-500"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kartu Status Stok -->
            <div class="p-6 text-center bg-white rounded-lg shadow-md dark:bg-slate-800">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Saat Ini</h4>
                <p class="my-2 text-5xl font-bold 
                    @if($product->stock_status == 'in_stock') text-green-500 
                    @elseif($product->stock_status == 'low_stock') text-yellow-500 
                    @else text-red-500 @endif">
                    {{ number_format($product->current_stock) }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->unit }}</p>
                <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                    Stok Minimum: {{ number_format($product->min_stock) }} {{ $product->unit }}
                </div>
            </div>

            <!-- Kartu Aksi -->
            <div class="p-6 bg-white rounded-lg shadow-md dark:bg-slate-800">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                 <div class="flex flex-col space-y-3">
                    <a href="{{ route('manajergudang.stock.in') }}?product_id={{ $product->id }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700">
                        <i class="mr-2 fas fa-plus"></i> Catat Barang Masuk
                    </a>
                    <a href="{{ route('manajergudang.stock.out') }}?product_id={{ $product->id }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700">
                        <i class="mr-2 fas fa-minus"></i> Catat Barang Keluar
                    </a>
                 </div>
            </div>
        </div>
    </div>
@endsection