@extends('layouts.dashboard')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('manajergudang.products.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400" title="Kembali ke Daftar Produk">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Produk</h1>
            </div>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Informasi lengkap dan riwayat transaksi untuk produk {{ $product->name }}.</p>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Informasi Produk --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">{{ $product->name }}</h3>
                    <div class="mt-4 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">SKU:</span>
                            <span class="font-mono text-gray-900 dark:text-white">{{ $product->sku }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Kategori:</span>
                            <span class="text-gray-900 dark:text-white">{{ $product->category->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Supplier:</span>
                            <span class="text-gray-900 dark:text-white">{{ $product->supplier->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Unit:</span>
                            <span class="text-gray-900 dark:text-white">{{ $product->unit }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Stok Minimum:</span>
                            <span class="text-gray-900 dark:text-white">{{ $product->min_stock }}</span>
                        </div>
                        <div class="border-t dark:border-slate-700 pt-4">
                            @php
                                $stock = $product->stockTransactions->where('type', 'Masuk')->sum('quantity') - $product->stockTransactions->where('type', 'Keluar')->sum('quantity');
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-600 dark:text-gray-400">Stok Saat Ini:</span>
                                <span class="text-2xl font-bold 
                                    @if($stock <= $product->min_stock) text-red-500 @else text-green-500 @endif">
                                    {{ $stock }}
                                </span>
                            </div>
                        </div>
                        @if($product->description)
                        <div class="border-t dark:border-slate-700 pt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Riwayat Transaksi --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Transaksi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pengguna</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-slate-700">
                                @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($transaction->date)->isoFormat('DD MMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->type == 'Masuk')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                <i class="fas fa-arrow-down mr-1"></i> Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                <i class="fas fa-arrow-up mr-1"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold @if($transaction->type == 'Masuk') text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                                        {{ $transaction->type == 'Masuk' ? '+' : '-' }} {{ $transaction->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction->user->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi untuk produk ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection