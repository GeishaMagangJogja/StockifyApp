@extends('layouts.dashboard')

@section('title', 'Analisis & Valuasi Stok')

@push('styles')
<style>
    .sortable-header { cursor: pointer; transition: background-color 0.2s ease-in-out; }
    .sortable-header:hover { background-color: rgba(0, 0, 0, 0.05); }
    .dark .sortable-header:hover { background-color: rgba(255, 255, 255, 0.05); }
    .sort-icon { transition: transform 0.2s ease-in-out, color 0.2s; }
    a:hover .sort-icon { color: #0d9488; } /* Warna Teal-600 saat hover */
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Header Halaman - Gaya Analitis -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analisis & Valuasi Stok</h1>
        <p class="mt-1 text-gray-600 dark:text-gray-400">Tinjauan analitis mengenai kondisi, nilai, dan kesehatan inventori.</p>
        <div class="mt-4 border-b border-gray-200 dark:border-slate-700"></div>
    </div>
    
    <!-- Kartu Statistik dengan Valuasi -->
    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card 
            icon="fa-dollar-sign" 
            color="green" 
            title="Total Nilai Stok" 
            :value="'Rp ' . number_format($stockSummary['total_value'], 0, ',', '.')" 
        />
        <x-stat-card icon="fa-boxes-stacked" color="cyan" title="Total Varian" :value="number_format($stockSummary['total'])" />
        <x-stat-card icon="fa-exclamation-circle" color="yellow" title="Varian Stok Rendah" :value="number_format($stockSummary['low'])" />
        <x-stat-card icon="fa-ban" color="red" title="Varian Stok Habis" :value="number_format($stockSummary['out'])" />
    </div>

    <!-- Panel Kontrol (Filter & Tabel) -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <!-- Form Filter -->
        <div class="p-6">
            <form action="{{ route('manajergudang.reports.stock') }}" method="GET" class="space-y-4">
                 <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="search" placeholder="Cari SKU atau Nama Produk..." value="{{ request('search') }}"
                           class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg md:col-span-1 dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-teal-500 focus:border-teal-500">
                    <select name="category_id" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="stock_status" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Status</option>
                        <option value="safe" @selected(request('stock_status') == 'safe')>Aman</option>
                        <option value="low" @selected(request('stock_status') == 'low')>Rendah</option>
                        <option value="out" @selected(request('stock_status') == 'out')>Habis</option>
                    </select>
                </div>
                 <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                 <input type="hidden" name="direction" value="{{ $sortDirection }}">
                 <div class="flex justify-end">
                    <button type="submit" class="flex items-center justify-center px-5 py-2 font-semibold text-white transition-all bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <i class="mr-2 fas fa-filter"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Analisis Stok -->
        <div class="overflow-x-auto border-t border-gray-200 dark:border-slate-700">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                 <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700/50 dark:text-gray-400">
                    <tr>
                        @php
                            function getSortLink($column, $sortBy, $sortDirection) {
                                $direction = ($sortBy == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $column, 'direction' => $direction]);
                            }
                        @endphp
                        <th scope="col" class="px-6 py-3 w-2/5 sortable-header">
                            <a href="{{ getSortLink('name', $sortBy, $sortDirection) }}" class="flex items-center">PRODUK<i class="ml-1.5 fas sort-icon @if($sortBy == 'name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/5 sortable-header">
                            <a href="{{ getSortLink('current_stock', $sortBy, $sortDirection) }}" class="flex items-center">KESEHATAN STOK<i class="ml-1.5 fas sort-icon @if($sortBy == 'current_stock') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right sortable-header">
                            <a href="{{ getSortLink('stock_value', $sortBy, $sortDirection) }}" class="inline-flex items-center">NILAI STOK (VALUASI)<i class="ml-1.5 fas sort-icon @if($sortBy == 'stock_value') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }} | Kategori: {{ $product->category->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    // Hitung rasio stok. Hindari pembagian dengan nol.
                                    $maxStockLevel = $product->min_stock > 0 ? $product->min_stock * 2 : $product->current_stock + 1;
                                    $stockRatio = $maxStockLevel > 0 ? ($product->current_stock / $maxStockLevel) * 100 : 0;
                                    $stockRatio = min($stockRatio, 100); // Batasi maksimal 100%

                                    $statusBarColor = 'bg-green-500'; // Default: Aman
                                    if ($product->stock_status_calculated == 'low_stock') $statusBarColor = 'bg-yellow-500';
                                    if ($product->stock_status_calculated == 'out_of_stock') $statusBarColor = 'bg-red-500';
                                @endphp
                                <div class="flex items-center justify-between mb-1 text-xs">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ number_format($product->current_stock) }} / min {{ number_format($product->min_stock) }}</span>
                                    <span class="font-bold {{ str_replace('bg', 'text', $statusBarColor) }}">{{ round($stockRatio) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-slate-700">
                                    <div class="{{ $statusBarColor }} h-1.5 rounded-full" style="width: {{ $stockRatio }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-mono text-base font-semibold text-gray-900 dark:text-white">Rp {{ number_format($product->stock_value, 0, ',', '.') }}</p>
                                <p class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ number_format($product->current_stock) }} x Rp {{ number_format($product->purchase_price) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-16 text-center"><x-empty-state title="Data Stok Tidak Ditemukan" message="Silakan sesuaikan filter pencarian Anda." icon="fa-search" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-slate-700">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection