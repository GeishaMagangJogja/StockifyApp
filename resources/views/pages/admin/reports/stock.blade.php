@extends('layouts.dashboard')

@section('title', 'Laporan Stok Barang')

@section('content')
{{-- Unified Card Layout --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
    
    {{-- Card Header: Title & Filters --}}
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Laporan Stok Barang</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Analisis stok produk berdasarkan kategori.</p>
            </div>
            
            {{-- Filter Form --}}
            <form method="GET" class="mt-4 sm:mt-0 flex items-center gap-4">
                <select name="category_id" class="w-52 px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-md font-medium shadow-sm transition">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.reports.stock') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">Reset</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            {{-- Enhanced Table Header --}}
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Stok Saat Ini</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            {{-- Zebra-striping and hover effect --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900 dark:text-white font-semibold">{{ $product->current_stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->current_stock <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span>
                                    Stok Habis
                                </span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                     <span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span>
                                    Stok Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                     <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>
                                    Stok Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>Tidak ada data stok ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($products->hasPages())
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection