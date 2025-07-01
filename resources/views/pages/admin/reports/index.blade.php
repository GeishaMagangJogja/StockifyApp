@extends('layouts.dashboard')

@section('title', 'Laporan Stok Barang')

@section('content')
<div class="mb-6">
    <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.reports.index') }}" class="hover:text-blue-600">Laporan</a>
        <span>/</span>
        <span>Stok Barang</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Stok Barang</h1>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
    {{-- Filter Section --}}
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <select name="category_id" class="w-full sm:w-48 px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <a href="{{ route('admin.reports.stock') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                Reset
            </a>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Stok Saat Ini</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Min. Stok</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Total Transaksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                            {{ $product->current_stock }} {{ $product->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-300">
                            {{ $product->minimum_stock }} {{ $product->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($product->current_stock <= $product->minimum_stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    Stok Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-300">
                            {{ $product->stock_transactions_count ?? 0 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-box-open fa-2x mb-2"></i>
                            <p>Tidak ada data produk ditemukan.</p>
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
