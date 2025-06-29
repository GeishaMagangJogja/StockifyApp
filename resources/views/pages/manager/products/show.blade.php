@extends('layouts.manager')

@section('title', 'Detail Produk')

@section('content')
<div class="mb-6">
    <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('manajergudang.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('manajergudang.products.index') }}" class="hover:text-blue-600">Produk</a>
        <span>/</span>
        <span>Detail</span>
    </div>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Produk</h1>
        <div class="flex space-x-2">
            <a href="{{ route('manajergudang.stock.history', ['product_id' => $product->id]) }}" class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-history"></i>Riwayat Stok
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-start">
                    @if($product->image)
                        <img class="object-cover w-20 h-20 mr-6 rounded-lg" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="flex items-center justify-center w-20 h-20 mr-6 bg-gray-300 rounded-lg">
                            <i class="text-2xl text-gray-500 fas fa-box"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                        <div class="flex items-center mt-2">
                            @if($product->stock == 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100">
                                    Stok Habis
                                </span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-800 dark:text-yellow-100">
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-100">
                                    Stok Normal
                                </span>
                            @endif
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $product->stock }} pcs (Min: {{ $product->min_stock }} pcs)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->supplier->name ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Stok Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                {{ $transaction->date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($transaction->type === 'Masuk')
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-200">
                                    Masuk
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-200">
                                    Keluar
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                {{ number_format($transaction->quantity) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                {{ $transaction->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                Tidak ada riwayat transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>

    <div>
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aksi Cepat</h3>
            </div>
            <div class="p-6">
                <a href="{{ route('manajergudang.stock.in', ['product_id' => $product->id]) }}" class="flex items-center justify-center w-full px-4 py-2 mb-4 text-white transition duration-150 bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-2 fas fa-plus"></i>Tambah Stok
                </a>
                <a href="{{ route('manajergudang.stock.out', ['product_id' => $product->id]) }}" class="flex items-center justify-center w-full px-4 py-2 mb-4 text-white transition duration-150 bg-red-600 rounded-lg hover:bg-red-700">
                    <i class="mr-2 fas fa-minus"></i>Kurangi Stok
                </a>
                <a href="{{ route('manajergudang.stock.opname') }}" class="flex items-center justify-center w-full px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-clipboard-check"></i>Stock Opname
                </a>
            </div>
        </div>

        <div class="mt-6 overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Statistik Stok</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex justify-between mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <span>Stok Tersedia</span>
                        <span>{{ $product->stock }} / {{ $product->stock + $product->min_stock * 3 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        @php
                            $percentage = min(100, ($product->stock / ($product->stock + $product->min_stock * 3)) * 100);
                            $color = $product->stock <= $product->min_stock ? 'bg-red-600' : ($product->stock <= ($product->min_stock * 2) ? 'bg-yellow-400' : 'bg-green-600');
                        @endphp
                        <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Masuk (30 hari)</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $incomingLast30Days }} pcs</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Keluar (30 hari)</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $outgoingLast30Days }} pcs</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Bulanan</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $monthlyAverage }} pcs/bulan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
