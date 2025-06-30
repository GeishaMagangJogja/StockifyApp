@extends('layouts.dashboard')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div>
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('manajergudang.products.index') }}" class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 rounded-full shadow hover:bg-gray-100 dark:hover:bg-slate-700 transition" title="Kembali">
                    <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $product->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                </div>
            </div>
        </div>

        {{-- Konten Detail --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Info & Status --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Kartu Info Utama --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=random' }}" alt="{{ $product->name }}">
                        <div>
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                             <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $product->category->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Supplier:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $product->supplier->name ?? 'N/A' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Unit:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $product->unit }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Stok Minimum:</span> <span class="font-medium text-gray-800 dark:text-white">{{ number_format($product->min_stock) }}</span></div>
                    </div>
                </div>
                {{-- Kartu Status Stok --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Stok Saat Ini</h4>
                    <p class="text-4xl font-bold {{ $product->current_stock <= $product->min_stock ? 'text-red-500' : 'text-green-500' }}">
                        {{ number_format($product->current_stock) }}
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan: Riwayat Transaksi --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Transaksi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal & Pengguna</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                                @forelse($product->stockTransactions->sortByDesc('date') as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y, H:i') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">oleh {{ $transaction->user->name ?? 'Sistem' }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->type == 'Masuk')<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-arrow-down mr-1.5"></i> Masuk</span>
                                            @else<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-arrow-up mr-1.5"></i> Keluar</span>@endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->type == 'Masuk' ? '+' : '-' }} {{ number_format($transaction->quantity) }}
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
        </div>
    </div>
</div>
@endsection