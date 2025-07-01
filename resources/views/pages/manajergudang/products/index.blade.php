@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Produk</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Lihat semua produk yang terdaftar dalam sistem.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
            {{-- Form Pencarian --}}
            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                <form action="{{ route('manajergudang.products.index') }}" method="GET">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('manajergudang.products.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            {{-- Kolom Produk dengan Avatar --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=random' }}" alt="{{ $product->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- Kolom Kategori --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            {{-- Kolom Stok --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="text-gray-900 dark:text-white">{{ number_format($product->current_stock) }} <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $product->unit }}</span></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Min: {{ number_format($product->minimum_stock) }}</div>
                            </td>
                            {{-- Kolom Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->current_stock <= 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Habis</span>
                                @elseif($product->current_stock <= $product->minimum_stock)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">Stok Rendah</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Tersedia</span>
                                @endif
                            </td>
                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                 <a href="{{ route('manajergudang.products.show', $product) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
                                     <i class="fas fa-eye"></i>
                                 </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Tidak Ada Produk</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if(request('search'))
                                            Tidak ada produk yang cocok dengan pencarian Anda.
                                        @else
                                            Sistem belum memiliki data produk.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-slate-700">{{ $products->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
