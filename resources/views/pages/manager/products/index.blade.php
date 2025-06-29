@extends('layouts.manager')

@section('title', 'Daftar Produk')

@section('content')
<div class="mb-6">
    <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('manajergudang.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <span>Produk</span>
    </div>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Produk</h1>
        <div class="flex space-x-2">
            <a href="{{ route('manajergudang.reports.stock') }}" class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-chart-bar"></i>Laporan Stok
            </a>
        </div>
    </div>
</div>

<div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <form action="{{ route('manajergudang.products.index') }}" method="GET">
            <div class="flex flex-wrap items-center gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari produk berdasarkan nama atau SKU..."
                       class="flex-1 min-w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                <select name="stock_status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status Stok</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                    <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>Stok Normal</option>
                </select>

                <button type="submit" class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Stok</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Status</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($product->image)
                                <img class="w-10 h-10 rounded-lg object-cover mr-4" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="w-10 h-10 bg-gray-300 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-box text-gray-500"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{ $product->category->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        <div class="flex items-center">
                            <span class="@if($product->stock <= $product->min_stock) text-red-600 dark:text-red-400 font-semibold @elseif($product->stock <= ($product->min_stock * 2)) text-yellow-600 dark:text-yellow-400 @endif">
                                {{ $product->stock }}
                            </span>
                            <span class="mx-2">/</span>
                            <span class="text-xs text-gray-400">Min: {{ $product->min_stock }}</span>
                        </div>
                        @if($product->stock <= $product->min_stock)
                        <div class="text-xs text-red-500 dark:text-red-400 mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>Perlu restock
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->stock == 0)
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100">
                                Stok Habis
                            </span>
                        @elseif($product->stock <= $product->min_stock)
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-800 dark:text-yellow-100">
                                Stok Rendah
                            </span>
                        @else
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-100">
                                Stok Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <a href="{{ route('manajergudang.products.show', $product->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('manajergudang.stock.in', ['product_id' => $product->id]) }}" class="ml-3 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Tambah Stok">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center py-8">
                            <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                            <p>Tidak ada data produk ditemukan</p>
                            @if(request('search') || request('stock_status'))
                                <a href="{{ route('manajergudang.products.index') }}" class="mt-2 text-blue-600 hover:text-blue-800">Reset Filter</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
