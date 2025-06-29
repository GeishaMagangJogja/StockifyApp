@extends('layouts.manager')

@section('title', 'Daftar Stok')
@section('header', 'Daftar Stok Barang')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Daftar Stok Barang
                </h3>
                <div class="mt-4 sm:mt-0">
                    <form action="{{ route('manajergudang.stock.index') }}" method="GET">
                        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                            <select name="stock_status" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                            <input type="text" name="search" placeholder="Cari produk..."
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ request('search') }}">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Produk
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Supplier
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Stok
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image)
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center dark:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->sku }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $product->category->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $product->supplier->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ number_format($product->stock) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->stock == 0)
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                    Habis
                                </span>
                                @elseif($product->stock <= $product->minimum_stock)
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                    Rendah
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                    Tersedia
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                Tidak ada produk yang ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
