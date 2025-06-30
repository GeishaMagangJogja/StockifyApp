@extends('layouts.dashboard')

@section('title', 'Detail Supplier: ' . $supplier->name)

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('manajergudang.suppliers.index') }}" class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 rounded-full shadow hover:bg-gray-100 dark:hover:bg-slate-700 transition" title="Kembali">
                <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $supplier->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Detail informasi dan produk yang disuplai.</p>
            </div>
        </div>

        {{-- Konten Detail --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Info Supplier --}}
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <img class="h-16 w-16 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=1e293b&color=fff" alt="{{ $supplier->name }}">
                        <div>
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $supplier->name }}</h3>
                             <p class="text-xs text-gray-500 dark:text-gray-400">Terdaftar sejak {{ $supplier->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start"><i class="fas fa-envelope fa-fw mt-1 mr-3 text-gray-400"></i><span class="font-medium text-gray-800 dark:text-white">{{ $supplier->email ?? '-' }}</span></div>
                        <div class="flex items-start"><i class="fas fa-phone fa-fw mt-1 mr-3 text-gray-400"></i><span class="font-medium text-gray-800 dark:text-white">{{ $supplier->phone }}</span></div>
                        <div class="flex items-start"><i class="fas fa-map-marker-alt fa-fw mt-1 mr-3 text-gray-400"></i><p class="text-gray-800 dark:text-white">{{ $supplier->address ?? 'Tidak ada alamat' }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Daftar Produk dari Supplier Ini --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk dari Supplier Ini ({{ $supplier->products->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stok Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                                @forelse($supplier->products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                            {{ $product->current_stock }} {{ $product->unit }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Supplier ini belum memiliki produk.</td></tr>
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