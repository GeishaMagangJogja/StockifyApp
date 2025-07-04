@extends('layouts.dashboard')

@section('title', 'Manajemen Data Produk')

@push('styles')
<style>
    .sortable-header { cursor: pointer; transition: background-color 0.2s ease-in-out; }
    .sortable-header:hover { background-color: rgba(0, 0, 0, 0.05); }
    .dark .sortable-header:hover { background-color: rgba(255, 255, 255, 0.05); }
    .sort-icon { transition: transform 0.2s ease-in-out, color 0.2s; }
    a:hover .sort-icon { color: #06b6d4; }
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Header Halaman -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen Data Produk</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Pusat data untuk semua varian produk yang ada di inventori.</p>
            </div>
        </div>
        <div class="mt-4 border-b border-gray-200 dark:border-slate-700"></div>
    </div>
    
    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card icon="fa-boxes-stacked" color="cyan" title="Total Varian Produk" :value="number_format($stockStats['total'])" />
        <x-stat-card icon="fa-shield-alt" color="green" title="Stok Aman" :value="number_format($stockStats['safe'])" />
        <x-stat-card icon="fa-exclamation-circle" color="yellow" title="Stok Rendah" :value="number_format($stockStats['low'])" />
        <x-stat-card icon="fa-ban" color="red" title="Stok Habis" :value="number_format($stockStats['out_of_stock'])" />
    </div>

    <!-- Panel Kontrol -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <!-- Form Filter -->
        <div class="p-6">
            <form action="{{ route('manajergudang.products.index') }}" method="GET">
                <div class="flex flex-col gap-4 md:flex-row">
                    <div class="relative flex-grow">
                         <i class="absolute text-gray-400 transform -translate-y-1/2 left-4 top-1/2 fas fa-search"></i>
                         <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama atau SKU produk..."
                                class="w-full py-2.5 pl-12 pr-4 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                     <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                     <input type="hidden" name="direction" value="{{ $sortDirection }}">
                     <button type="submit" class="flex items-center justify-center px-5 py-2 font-semibold text-white transition-all bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        <i class="mr-2 fas fa-search"></i>Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('manajergudang.products.index') }}" class="flex items-center justify-center px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-slate-600 dark:text-gray-200 dark:hover:bg-slate-500" title="Reset">
                           <i class="fas fa-undo"></i>
                        </a>
                    @endif
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
                        <th scope="col" class="px-6 py-3 w-2/5 sortable-header">
                            <a href="{{ getSortLink('name', $sortBy, $sortDirection) }}" class="flex items-center">PRODUK<i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 sortable-header">
                             <a href="{{ getSortLink('purchase_price', $sortBy, $sortDirection) }}" class="flex items-center">HARGA BELI<i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'purchase_price') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                         <th scope="col" class="px-6 py-3 sortable-header">
                             <a href="{{ getSortLink('selling_price', $sortBy, $sortDirection) }}" class="flex items-center">HARGA JUAL<i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'selling_price') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center sortable-header">
                            <a href="{{ getSortLink('current_stock', $sortBy, $sortDirection) }}" class="inline-flex items-center">STOK<i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'current_stock') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
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
                            <td class="px-6 py-4 font-mono text-gray-800 dark:text-gray-200">{{ $product->formatted_purchase_price }}</td>
                            <td class="px-6 py-4 font-mono text-gray-800 dark:text-gray-200">{{ $product->formatted_selling_price }}</td>
                            <td class="px-6 py-4 text-center">
                               <div class="text-base font-bold text-gray-900 dark:text-white">{{ number_format($product->current_stock) }}</div>
                               <x-badge-status type="{{ $product->stock_status == 'in_stock' ? 'success' : ($product->stock_status == 'low_stock' ? 'warning' : 'danger') }}" text="{{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}" />
                            </td>
                            <td class="px-6 py-4 text-center">
                                 <a href="{{ route('manajergudang.products.show', $product) }}" class="px-3 py-1.5 text-sm font-medium text-cyan-700 bg-cyan-100 rounded-md hover:bg-cyan-200 dark:bg-cyan-900/50 dark:text-cyan-300 dark:hover:bg-cyan-900">
                                     Detail
                                 </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center"><x-empty-state title="Data Produk Tidak Ditemukan" message="Coba kata kunci pencarian yang lain." icon="fa-search" /></td></tr>
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