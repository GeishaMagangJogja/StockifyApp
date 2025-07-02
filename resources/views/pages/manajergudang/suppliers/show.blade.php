@extends('layouts.dashboard')

@section('title', 'Detail Supplier: ' . $supplier->name)

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div class="py-8">
        <div class="flex items-center mb-8 space-x-4">
            <a href="{{ route('manajergudang.suppliers.index') }}" class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700" title="Kembali"><i class="text-gray-600 fas fa-arrow-left dark:text-gray-300"></i></a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $supplier->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Detail informasi dan produk yang disuplai.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- Kolom Kiri: Info Supplier --}}
            <div class="space-y-8 lg:col-span-1">
                <div class="p-6 bg-white rounded-lg shadow-md dark:bg-slate-800">
                    <div class="flex items-center mb-6 space-x-4">
                        <img class="object-cover w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=1e293b&color=fff" alt="{{ $supplier->name }}">
                        <div>
                             <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $supplier->name }}</h3>
                             <p class="text-xs text-gray-500 dark:text-gray-400">Terdaftar sejak {{ $supplier->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm">
                        {{-- [FIX] Menambahkan Contact Person --}}
                        <div class="flex items-start"><i class="w-5 mt-1 mr-3 text-gray-400 fas fa-user-tie fa-fw"></i><div class="flex-1"><p class="font-medium text-gray-500 dark:text-gray-400">Contact Person</p><p class="text-gray-800 dark:text-white">{{ $supplier->contact_person ?? '-' }}</p></div></div>
                        <div class="flex items-start"><i class="w-5 mt-1 mr-3 text-gray-400 fas fa-phone fa-fw"></i><div class="flex-1"><p class="font-medium text-gray-500 dark:text-gray-400">Telepon</p><p class="text-gray-800 dark:text-white">{{ $supplier->phone ?? '-' }}</p></div></div>
                        <div class="flex items-start"><i class="w-5 mt-1 mr-3 text-gray-400 fas fa-envelope fa-fw"></i><div class="flex-1"><p class="font-medium text-gray-500 dark:text-gray-400">Email</p><p class="text-gray-800 dark:text-white">{{ $supplier->email ?? '-' }}</p></div></div>
                        <div class="flex items-start"><i class="w-5 mt-1 mr-3 text-gray-400 fas fa-map-marker-alt fa-fw"></i><div class="flex-1"><p class="font-medium text-gray-500 dark:text-gray-400">Alamat</p><p class="text-gray-800 dark:text-white">{{ $supplier->address ?? 'Tidak ada alamat' }}</p></div></div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Daftar Produk dari Supplier Ini --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md dark:bg-slate-800">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Produk dari Supplier Ini ({{ $supplier->products->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-slate-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-300">Nama Produk</th>
                                    <th class="px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Stok Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-slate-700 dark:bg-slate-800">
                                @forelse($supplier->products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <img class="object-cover w-10 h-10 rounded-md" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" alt="{{ $product->name }}">
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{ $product->current_stock }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $product->unit }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Supplier ini belum memiliki produk terdaftar.</td></tr>
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