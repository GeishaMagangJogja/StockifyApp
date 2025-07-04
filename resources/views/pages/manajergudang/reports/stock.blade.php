@extends('layouts.dashboard')

@section('title', 'Laporan Analisis Stok')

@push('styles')
<style>
    .sortable-header { cursor: pointer; transition: background-color 0.2s ease-in-out; }
    .sortable-header:hover { background-color: rgba(0, 0, 0, 0.05); }
    .dark .sortable-header:hover { background-color: rgba(255, 255, 255, 0.05); }
    .sort-icon { transition: transform 0.2s ease-in-out, color 0.2s; }
    a:hover .sort-icon { color: #06b6d4; } /* Warna Cyan-500 saat hover */
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Header Halaman - Gaya Manajer -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Analisis Stok Gudang</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Tinjauan komprehensif mengenai status dan valuasi stok.</p>
            </div>
            <a href="{{ route('manajergudang.reports.stock') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600">
                <i class="mr-2 fas fa-sync-alt"></i>Reset
            </a>
        </div>
        <div class="mt-4 border-b border-gray-200 dark:border-slate-700"></div>
    </div>
    
    <!-- Kartu Statistik - Gaya Manajer (Lebih minimalis) -->
    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card icon="fa-boxes-stacked" color="cyan" title="Total Varian Produk" value="{{ $stockSummary['total'] }}" />
        <x-stat-card icon="fa-shield-alt" color="green" title="Stok Aman" value="{{ $stockSummary['safe'] }}" />
        <x-stat-card icon="fa-exclamation-circle" color="yellow" title="Stok Rendah" value="{{ $stockSummary['low'] }}" />
        <x-stat-card icon="fa-ban" color="red" title="Stok Habis" value="{{ $stockSummary['out'] }}" />
    </div>

    <!-- Panel Kontrol (Filter & Tabel) -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <!-- Form Filter -->
        <div class="p-6">
            <form action="{{ route('manajergudang.reports.stock') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="text" name="search" placeholder="Cari SKU atau Nama Produk..." value="{{ request('search') }}"
                           class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg md:col-span-1 dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                    <select name="category_id" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="stock_status" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Semua Status</option>
                        <option value="safe" @selected(request('stock_status') == 'safe')>Aman</option>
                        <option value="low" @selected(request('stock_status') == 'low')>Rendah</option>
                        <option value="out" @selected(request('stock_status') == 'out')>Habis</option>
                    </select>
                </div>
                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                <input type="hidden" name="direction" value="{{ $sortDirection }}">
                <div class="flex justify-end">
                    <button type="submit" class="flex items-center justify-center px-5 py-2 font-semibold text-white transition-all bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        <i class="mr-2 fas fa-search"></i>Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel -->
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
                        <th scope="col" class="px-6 py-3 sortable-header">
                             <a href="{{ getSortLink('name', $sortBy, $sortDirection) }}" class="flex items-center">
                                PRODUK
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 sortable-header">
                             <a href="{{ getSortLink('category_name', $sortBy, $sortDirection) }}" class="flex items-center">
                                KATEGORI
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'category_name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center sortable-header">
                            <a href="{{ getSortLink('current_stock', $sortBy, $sortDirection) }}" class="inline-flex items-center">
                                STOK SAAT INI
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'current_stock') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center sortable-header">
                             <a href="{{ getSortLink('stock_status', $sortBy, $sortDirection) }}" class="inline-flex items-center">
                                STATUS
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'stock_status') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="object-cover w-10 h-10 mr-4 rounded-md" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=1e293b&color=fff' }}" alt="{{ $product->name }}">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium text-slate-800 bg-slate-100 rounded-full dark:bg-slate-600 dark:text-slate-200">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-base font-bold text-gray-900 dark:text-white">{{ number_format($product->current_stock) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Batas Min: {{ number_format($product->min_stock) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-badge-status type="{{ $product->stock_status_calculated == 'safe' ? 'success' : ($product->stock_status_calculated == 'low_stock' ? 'warning' : 'danger') }}" text="{{ $product->stock_status_calculated == 'safe' ? 'Aman' : ($product->stock_status_calculated == 'low_stock' ? 'Rendah' : 'Habis') }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <x-empty-state title="Data Stok Tidak Ditemukan"
                                    message="Silakan sesuaikan filter pencarian Anda."
                                    icon="fa-search" />
                            </td>
                        </tr>
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