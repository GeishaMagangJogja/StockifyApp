@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white"><i class="mr-3 text-blue-500 fas fa-boxes"></i>Daftar Produk</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Lihat semua produk yang terdaftar dalam sistem.</p>
        </div>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Varian</h4>
                        <p class="mt-1 text-4xl font-bold text-gray-800 dark:text-white">{{ number_format($stockStats['total']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full dark:bg-blue-900/30"><i class="text-xl text-blue-500 fas fa-boxes-stacked"></i></div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Aman</h4>
                        <p class="mt-1 text-4xl font-bold text-green-500">{{ number_format($stockStats['safe']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full dark:bg-green-900/30"><i class="text-xl text-green-500 fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Rendah</h4>
                        <p class="mt-1 text-4xl font-bold text-yellow-500">{{ number_format($stockStats['low']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full dark:bg-yellow-900/30"><i class="text-xl text-yellow-500 fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Habis</h4>
                        <p class="mt-1 text-4xl font-bold text-red-500">{{ number_format($stockStats['out_of_stock']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full dark:bg-red-900/30"><i class="text-xl text-red-500 fas fa-times-circle"></i></div>
                </div>
            </div>
        </div>

        {{-- Panel Filter & Tabel --}}
        <div class="overflow-hidden bg-white rounded-xl shadow-lg dark:bg-slate-800">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <form action="{{ route('manajergudang.products.index') }}" method="GET">
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><i class="text-gray-400 fas fa-search"></i></div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="w-full px-4 py-2.5 pl-12 pr-4 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-center gap-2">
                             <button type="submit" class="flex items-center justify-center w-full px-6 py-2.5 text-white bg-blue-600 rounded-lg md:w-auto hover:bg-blue-700"><i class="mr-2 fas fa-filter"></i>Cari</button>
                            @if(request('search'))
                                <a href="{{ route('manajergudang.products.index') }}" class="p-2.5 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300" title="Reset"><i class="fas fa-undo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"><i class="mr-2 fas fa-box"></i>Produk</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"><i class="mr-2 fas fa-tag"></i>Kategori</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"><i class="mr-2 fas fa-cubes"></i>Stok</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300"><i class="mr-2 fas fa-info-circle"></i>Status</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-300"><i class="mr-2 fas fa-eye"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-slate-700 dark:bg-slate-800">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="object-cover w-10 h-10 mr-4 rounded-lg" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" alt="{{ $product->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4"><span class="inline-block px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">{{ $product->category->name ?? 'N/A' }}</span></td>
                            <td class="px-6 py-4 text-sm"><div class="font-semibold text-gray-900 dark:text-white">{{ number_format($product->current_stock) }} <span class="text-xs font-normal text-gray-500">{{ $product->unit }}</span></div><div class="text-xs text-gray-500 dark:text-gray-400">Min: {{ number_format($product->min_stock) }}</div></td>
                            <td class="px-6 py-4">@if($product->stock_status == 'out_of_stock')<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300"><i class="mr-1.5 fas fa-times-circle"></i>Habis</span>@elseif($product->stock_status == 'low_stock')<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900/50 dark:text-yellow-300"><i class="mr-1.5 fas fa-exclamation-triangle"></i>Rendah</span>@else<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300"><i class="mr-1.5 fas fa-check-circle"></i>Aman</span>@endif</td>
                            <td class="px-6 py-4 text-sm font-medium text-center"><a href="{{ route('manajergudang.products.show', $product) }}" class="inline-flex items-center justify-center w-9 h-9 text-blue-600 transition-colors duration-150 rounded-full hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/30" title="Lihat Detail"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400"><div class="flex flex-col items-center"><i class="mb-4 text-5xl fas fa-box-open opacity-50"></i><p class="text-lg">Tidak ada produk ditemukan.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())<div class="p-4 border-t border-gray-200 dark:border-slate-700">{{ $products->appends(request()->query())->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection