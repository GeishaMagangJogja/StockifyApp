@extends('layouts.dashboard')

@section('title', 'Profil Supplier: ' . $supplier->name)

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('manajergudang.suppliers.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="mr-2 fas fa-arrow-left"></i>
            Kembali ke Daftar Supplier
        </a>
    </div>

    <!-- Header Profil Supplier -->
    <div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <div class="flex flex-col items-center gap-6 text-center md:flex-row md:text-left">
            <img class="object-cover w-24 h-24 rounded-full shadow-lg ring-4 ring-white dark:ring-slate-700"
                 src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=0891b2&color=fff&size=128"
                 alt="{{ $supplier->name }}">
            <div class="flex-grow">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supplier->name }}</h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Mitra Supplier sejak {{ $supplier->created_at->format('d F Y') }}</p>
                <div class="flex flex-wrap items-center justify-center gap-4 mt-3 text-sm text-gray-600 md:justify-start dark:text-gray-300">
                    <span class="inline-flex items-center"><i class="w-4 mr-2 fas fa-user-tie fa-fw"></i>{{ $supplier->contact_person }}</span>
                    <span class="inline-flex items-center"><i class="w-4 mr-2 fas fa-phone fa-fw"></i>{{ $supplier->phone }}</span>
                    <span class="inline-flex items-center"><i class="w-4 mr-2 fas fa-envelope fa-fw"></i>{{ $supplier->email }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kartu KPI Supplier -->
    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        <x-stat-card icon="fa-boxes" color="cyan" title="Total Produk Disuplai" :value="number_format($supplierStats['total_products'])" />
        <x-stat-card icon="fa-cubes" color="purple" title="Total Unit Diterima" :value="number_format($supplierStats['total_units_supplied'])" />
        <div class="p-5 bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Terakhir</p>
                <i class="text-xl text-yellow-500 fas fa-calendar-check opacity-75"></i>
            </div>
            @if($supplierStats['last_transaction_date'])
            <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($supplierStats['last_transaction_date'])->format('d M Y') }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($supplierStats['last_transaction_date'])->diffForHumans() }}</p>
            @else
            <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">-</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada transaksi</p>
            @endif
        </div>
    </div>
    
    <!-- Panel Daftar Produk -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <div class="p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Produk dari {{ $supplier->name }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700/50 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-2/5">Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-center">Harga Beli</th>
                        <th scope="col" class="px-6 py-3 text-center">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier->products as $product)
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="object-cover w-10 h-10 mr-4 rounded-md" 
                                         src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=1e293b&color=fff' }}" 
                                         alt="{{ $product->name }}">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                               <span class="px-2 py-1 text-xs font-medium text-slate-800 bg-slate-100 rounded-full dark:bg-slate-600 dark:text-slate-200">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-gray-800 dark:text-gray-200">
                                {{ $product->formatted_purchase_price }}
                            </td>
                            <td class="px-6 py-4 text-center">
                               <div class="text-base font-bold text-gray-900 dark:text-white">{{ number_format($product->current_stock) }}</div>
                               <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->unit }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <x-empty-state title="Belum Ada Produk"
                                    message="Supplier ini belum memiliki produk yang terhubung dengannya."
                                    icon="fa-box-open" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection