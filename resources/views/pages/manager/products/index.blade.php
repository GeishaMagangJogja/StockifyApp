@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Produk</h1>
        <p class="text-gray-600 dark:text-gray-400">Lihat semua produk yang terdaftar dalam sistem.</p>
    </div>

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-slate-800">
        {{-- Form Pencarian Sederhana --}}
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <form action="{{ route('manajergudang.products.index') }}" method="GET">
                <div class="flex items-center gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="flex-1 px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700"><i class="fas fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('manajergudang.products.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Kategori</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Stok</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-800 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                        {{-- Kolom Produk --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->sku }}</p>
                        </td>
                        {{-- Kolom Kategori --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Kolom Stok --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $product->current_stock }} {{ $product->unit }}</span>
                                <span class="text-xs text-gray-500">Min: {{ $product->min_stock }}</span>
                            </div>
                        </td>
                        {{-- Kolom Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->current_stock <= 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Habis</span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Stok Rendah</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Normal</span>
                            @endif
                        </td>
                        {{-- Kolom Aksi (Read-Only) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                             <a href="{{ route('manajergudang.products.show', $product) }}" title="Lihat Detail"><i class="text-blue-500 fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada data produk ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">{{ $products->appends(request()->query())->links() }}</div>
        @endif
    </div>
@endsection