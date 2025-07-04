@extends('layouts.dashboard')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Tombol Kembali & Header -->
    <div class="flex flex-col gap-4 mb-8 md:items-center md:flex-row md:justify-between">
        <a href="{{ route('manajergudang.products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="mr-2 fas fa-arrow-left"></i>
            Kembali ke Daftar Produk
        </a>
        <div class="flex items-center gap-3">
             <a href="{{ route('manajergudang.stock.in') }}?product_id={{ $product->id }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 md:w-auto">
                <i class="mr-2 fas fa-plus"></i> Catat Barang Masuk
            </a>
            <a href="{{ route('manajergudang.stock.out') }}?product_id={{ $product->id }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-red-600 border border-transparent rounded-lg shadow-sm hover:bg-red-700 md:w-auto">
                <i class="mr-2 fas fa-minus"></i> Catat Barang Keluar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Kolom Kiri: Info Utama & Riwayat -->
        <div class="space-y-8 lg:col-span-2">
            <!-- Kartu Info Produk -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
                 <div class="flex flex-col gap-6 p-6 md:flex-row">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=1e293b&color=fff&size=128' }}" alt="{{ $product->name }}" class="object-cover w-full h-48 rounded-lg md:w-32 md:h-32">
                    <div class="flex-1">
                        <span class="px-2 py-1 mb-2 text-xs font-medium text-cyan-800 bg-cyan-100 rounded-full dark:bg-cyan-900/50 dark:text-cyan-300">{{ $product->category->name ?? 'N/A' }}</span>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
                    </div>
                </div>
                 <div class="grid grid-cols-2 gap-px bg-gray-100 dark:bg-slate-700/50">
                    <div class="p-4 bg-white dark:bg-slate-800"><p class="text-xs text-gray-500">Harga Beli</p><p class="font-semibold text-gray-900 dark:text-white">{{ $product->formatted_purchase_price }}</p></div>
                    <div class="p-4 bg-white dark:bg-slate-800"><p class="text-xs text-gray-500">Harga Jual</p><p class="font-semibold text-gray-900 dark:text-white">{{ $product->formatted_selling_price }}</p></div>
                </div>
            </div>

            <!-- Kartu Riwayat Transaksi -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
                <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Transaksi Terakhir</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700/50 dark:text-gray-400">
                            <tr><th scope="col" class="px-6 py-3">Tanggal</th><th scope="col" class="px-6 py-3">Tipe</th><th scope="col" class="px-6 py-3">Oleh</th><th scope="col" class="px-6 py-3 text-right">Jumlah</th></tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockTransactions as $transaction)
                            <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $transaction->date->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4"><x-badge-status type="{{ $transaction->type == 'Masuk' ? 'success' : 'danger' }}" text="{{ $transaction->type }}" /></td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $transaction->user->name ?? 'Sistem' }}</td>
                                <td class="px-6 py-4 font-semibold text-right {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">{{ $transaction->type == 'Masuk' ? '+' : '-' }}{{ number_format($transaction->quantity) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-10 text-center"><x-empty-state title="Belum Ada Transaksi" message="Produk ini belum memiliki riwayat pergerakan stok." icon="fa-exchange-alt" /></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status Stok & Detail Lainnya -->
        <div class="space-y-6">
            <div class="p-6 text-center bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Saat Ini</h4>
                <p class="my-2 text-5xl font-bold @if($product->stock_status == 'in_stock') text-green-500 @elseif($product->stock_status == 'low_stock') text-yellow-500 @else text-red-500 @endif">
                    {{ number_format($product->current_stock) }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $product->unit }}</p>
                <hr class="my-4 border-gray-200 dark:border-slate-700">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Stok Minimum</span>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($product->min_stock) }} {{ $product->unit }}</span>
                </div>
            </div>
             <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Tambahan</h3>
                 <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Supplier</span><span class="font-semibold text-right text-gray-900 dark:text-white">{{ $product->supplier->name ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tgl. Dibuat</span><span class="font-semibold text-gray-900 dark:text-white">{{ $product->created_at->format('d M Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Update Terakhir</span><span class="font-semibold text-gray-900 dark:text-white">{{ $product->updated_at->diffForHumans() }}</span></div>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection