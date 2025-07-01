@extends('layouts.dashboard')

@section('title', 'Laporan Stok Produk')

@section('content')
<div class="container px-4 mx-auto sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Stok Produk</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Ringkasan kondisi stok untuk semua produk dalam sistem.</p>
            </div>
        </div>

        {{-- Kartu Ringkasan Stok --}}
        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800"><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Varian Produk</h4><p class="mt-1 text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($products->total()) }}</p></div>
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800"><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Stok Rendah</h4><p class="mt-1 text-3xl font-bold text-yellow-500">{{ $products->filter(fn($p) => $p->current_stock <= $p->minimum_stock && $p->current_stock > 0)->count() }}</p></div>
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800"><h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Stok Habis</h4><p class="mt-1 text-3xl font-bold text-red-500">{{ $products->filter(fn($p) => $p->current_stock <= 0)->count() }}</p></div>
        </div>

        {{-- Panel Filter & Tabel --}}
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-slate-800">
            <div class="p-4 border-b dark:border-slate-700">
                <form action="{{ route('manajergudang.reports.stock') }}" method="GET">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><i class="text-gray-400 fas fa-filter"></i></div>
                            <select name="category_id" id="category_id" class="w-full py-2 pl-10 pr-4 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                                <option value="">Filter Berdasarkan Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('category_id'))
                        <a href="{{ route('manajergudang.reports.stock') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            {{-- Memberi lebar spesifik pada kolom --}}
                            <th class="w-2/5 px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                            <th class="w-1/5 px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Kategori</th>
                            <th class="w-1/5 px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Stok</th>
                            <th class="w-1/5 px-6 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:bg-slate-800 dark:divide-slate-700">
                        @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            {{-- Kolom Produk --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="object-cover w-10 h-10 mr-4 rounded-lg" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=random&size=128' }}" alt="{{ $product->name }}">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            {{-- Kolom Kategori (rata tengah) --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            {{-- Kolom Stok (rata tengah) --}}
                            <td class="px-6 py-4 text-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($product->current_stock) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ number_format($product->min_stock) }} {{ $product->unit }}</p>
                            </td>
                            {{-- Kolom Status (rata tengah) --}}
                            <td class="px-6 py-4 text-center">
                                @if($product->current_stock <= 0)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300"><i class="fas fa-times-circle mr-1.5"></i>Habis</span>
                                @elseif($product->current_stock <= $product->min_stock)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300"><i class="fas fa-exclamation-triangle mr-1.5"></i>Rendah</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300"><i class="fas fa-check-circle mr-1.5"></i>Tersedia</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"><i class="mb-4 text-4xl fas fa-box-open"></i><p>Tidak ada data untuk ditampilkan.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="p-4 border-t dark:border-slate-700">{{ $products->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
