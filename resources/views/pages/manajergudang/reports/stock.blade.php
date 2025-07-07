@extends('layouts.dashboard')

@section('title', 'Analisis & Valuasi Stok')

@push('styles')
<style>
    .sortable-header { 
        cursor: pointer; 
        transition: all 0.3s ease; 
        position: relative;
    }
    .sortable-header:hover { 
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
        transform: translateY(-1px);
    }
    .dark .sortable-header:hover { 
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
    }
    .sort-icon { 
        transition: all 0.3s ease; 
        opacity: 0.6;
    }
    .sortable-header:hover .sort-icon { 
        opacity: 1;
        color: #0d9488; 
        transform: scale(1.1);
    }
    .stat-card-glow {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    .stat-card-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    .filter-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
    }
    .dark .filter-section {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
    }
    .table-row-hover {
        transition: all 0.2s ease;
    }
    .table-row-hover:hover {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.03) 0%, rgba(5, 150, 105, 0.03) 100%);
        transform: translateX(4px);
    }
    .dark .table-row-hover:hover {
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%);
    }
    .stock-progress {
        background: linear-gradient(90deg, #e5e7eb 0%, #d1d5db 100%);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .dark .stock-progress {
        background: linear-gradient(90deg, #374151 0%, #4b5563 100%);
    }
    .stock-bar {
        background: linear-gradient(90deg, var(--bar-color-start), var(--bar-color-end));
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .gradient-border {
        background: linear-gradient(135deg, #14b8a6, #059669);
        padding: 1px;
        border-radius: 0.75rem;
    }
    .gradient-border-content {
        background: white;
        border-radius: 0.688rem;
    }
    .dark .gradient-border-content {
        background: #1e293b;
    }
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-slate-50 to-gray-100 dark:from-slate-900 dark:to-slate-800 min-h-screen">

    <!-- Header Halaman - Enhanced -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Analisis & Valuasi Stok
                </h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                    Tinjauan analitis mengenai kondisi, nilai, dan kesehatan inventori
                </p>
            </div>
            <div class="hidden md:flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
        <div class="mt-6 h-px bg-gradient-to-r from-teal-200 via-emerald-200 to-transparent dark:from-teal-700 dark:via-emerald-700"></div>
    </div>
    
    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Nilai Stok -->
        <div class="stat-card-glow bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Nilai Stok</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($stockSummary['total_value'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-trending-up mr-1"></i>
                    <span>Valuasi Aktif</span>
                </div>
            </div>
        </div>

        <!-- Total Varian -->
        <div class="stat-card-glow bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Varian</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stockSummary['total']) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-boxes-stacked text-lg"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                <div class="flex items-center text-sm text-cyan-600 dark:text-cyan-400">
                    <i class="fas fa-cube mr-1"></i>
                    <span>Produk Aktif</span>
                </div>
            </div>
        </div>

        <!-- Stok Rendah -->
        <div class="stat-card-glow bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Stok Rendah</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stockSummary['low']) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg animate-pulse-slow">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                <div class="flex items-center text-sm text-amber-600 dark:text-amber-400">
                    <i class="fas fa-arrow-down mr-1"></i>
                    <span>Perlu Perhatian</span>
                </div>
            </div>
        </div>

        <!-- Stok Habis -->
        <div class="stat-card-glow bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stockSummary['out']) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-ban text-lg"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span>Segera Restock</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Panel -->
    <div class="gradient-border mb-8">
        <div class="gradient-border-content">
            <div class="filter-section rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center text-white mr-3">
                        <i class="fas fa-filter text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter & Pencarian</h3>
                </div>
                
                <form action="{{ route('manajergudang.reports.stock') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari SKU atau Nama Produk..." value="{{ request('search') }}"
                                   class="w-full px-4 py-3 pl-10 text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <select name="category_id" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                            <option value="">🏷️ Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="stock_status" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-xl shadow-sm dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                            <option value="">📊 Semua Status</option>
                            <option value="safe" @selected(request('stock_status') == 'safe')>✅ Aman</option>
                            <option value="low" @selected(request('stock_status') == 'low')>⚠️ Rendah</option>
                            <option value="out" @selected(request('stock_status') == 'out')>❌ Habis</option>
                        </select>
                    </div>
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="direction" value="{{ $sortDirection }}">
                    <div class="flex justify-end">
                        <button type="submit" class="flex items-center justify-center px-6 py-3 font-semibold text-white bg-gradient-to-r from-teal-600 to-emerald-600 rounded-xl shadow-lg hover:from-teal-700 hover:to-emerald-700 focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all transform hover:scale-105">
                            <i class="mr-2 fas fa-search"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-slate-700 dark:to-slate-800 px-6 py-4 border-b border-gray-200 dark:border-slate-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-table mr-2 text-teal-600"></i>
                Data Analisis Stok
            </h3>
        </div>
        
        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-slate-700 dark:text-gray-300">
                    <tr>
                        @php
                            function getSortLink($column, $sortBy, $sortDirection) {
                                $direction = ($sortBy == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $column, 'direction' => $direction]);
                            }
                        @endphp
                        <th scope="col" class="px-6 py-4 w-2/5 sortable-header">
                            <a href="{{ getSortLink('name', $sortBy, $sortDirection) }}" class="flex items-center font-semibold">
                                <i class="fas fa-box mr-2"></i>
                                PRODUK
                                <i class="ml-2 fas sort-icon @if($sortBy == 'name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 w-1/5 sortable-header">
                            <a href="{{ getSortLink('current_stock', $sortBy, $sortDirection) }}" class="flex items-center font-semibold">
                                <i class="fas fa-heartbeat mr-2"></i>
                                KESEHATAN STOK
                                <i class="ml-2 fas sort-icon @if($sortBy == 'current_stock') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-4 text-right sortable-header">
                            <a href="{{ getSortLink('stock_value', $sortBy, $sortDirection) }}" class="inline-flex items-center font-semibold">
                                <i class="fas fa-dollar-sign mr-2"></i>
                                NILAI STOK
                                <i class="ml-2 fas sort-icon @if($sortBy == 'stock_value') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse ($products as $product)
                        <tr class="table-row-hover bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-750">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 dark:from-teal-900 dark:to-emerald-900 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-cube text-teal-600 dark:text-teal-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white text-base">{{ $product->name }}</p>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-300">
                                                <i class="fas fa-barcode mr-1"></i>
                                                {{ $product->sku }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $maxStockLevel = $product->min_stock > 0 ? $product->min_stock * 2 : $product->current_stock + 1;
                                    $stockRatio = $maxStockLevel > 0 ? ($product->current_stock / $maxStockLevel) * 100 : 0;
                                    $stockRatio = min($stockRatio, 100);

                                    $statusConfig = [
                                        'out_of_stock' => ['color' => 'red', 'label' => 'Habis', 'icon' => 'ban'],
                                        'low_stock' => ['color' => 'yellow', 'label' => 'Rendah', 'icon' => 'exclamation-triangle'],
                                        'safe' => ['color' => 'green', 'label' => 'Aman', 'icon' => 'check-circle']
                                    ];
                                    $status = $statusConfig[$product->stock_status_calculated] ?? $statusConfig['safe'];
                                @endphp
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-{{ $status['icon'] }} text-{{ $status['color'] }}-500"></i>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ number_format($product->current_stock) }} / {{ number_format($product->min_stock) }}
                                            </span>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800 dark:bg-{{ $status['color'] }}-900 dark:text-{{ $status['color'] }}-300">
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                    <div class="w-full stock-progress rounded-full h-2">
                                        <div class="stock-bar h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ $stockRatio }}%; 
                                                    --bar-color-start: {{ $status['color'] === 'green' ? '#10b981' : ($status['color'] === 'yellow' ? '#f59e0b' : '#ef4444') }}; 
                                                    --bar-color-end: {{ $status['color'] === 'green' ? '#059669' : ($status['color'] === 'yellow' ? '#d97706' : '#dc2626') }};">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ round($stockRatio) }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="space-y-1">
                                    <p class="text-xl font-bold text-gray-900 dark:text-white font-mono">
                                        Rp {{ number_format($product->stock_value, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        {{ number_format($product->current_stock) }} × Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                                    </p>
                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300">
                                        <i class="fas fa-calculator mr-1"></i>
                                        Valuasi
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                        <i class="fas fa-search text-2xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Stok Tidak Ditemukan</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mt-1">Silakan sesuaikan filter pencarian Anda</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection