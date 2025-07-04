@extends('layouts.dashboard')

@section('title', 'Laporan Stok Barang')

@section('content')
<!-- Header Section with Glass Effect -->
<div class="relative mb-8 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl backdrop-blur-sm"></div>
    <div class="relative p-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-4 space-x-2 text-sm">
            <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                <i class="mr-2 fas fa-file-alt"></i>Laporan
            </span>
            <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                <i class="mr-2 fas fa-boxes"></i>Stok Barang
            </span>
        </nav>

        <!-- Title Section -->
        <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="mr-3 text-blue-600 fas fa-chart-pie dark:text-blue-400"></i>
                    Laporan Stok Barang
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Analisis stok produk berdasarkan kategori dan status stok
                    @if(request('category_id'))
                        <span class="font-medium text-blue-600 dark:text-blue-400">(Filter: {{ $categories->find(request('category_id'))->name }})</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="mb-8 overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
    <div class="p-6">
        <form method="GET" class="flex flex-col items-start gap-4 sm:flex-row sm:items-end">
            <div class="flex flex-col w-full space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 sm:w-auto">
                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm sm:w-52 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div>
                    <label for="stock_status" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status Stok</label>
                    <select name="stock_status" id="stock_status" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm sm:w-48 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Status --</option>
                        @php
                            $statusOptions = [
                                'low' => 'Stok Menipis',
                                'out' => 'Stok Habis',
                                'safe' => 'Stok Aman',
                            ];
                        @endphp
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('stock_status') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center w-full space-x-3 sm:w-auto">
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                    <i class="mr-2 fas fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.reports.stock') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 focus:outline-none dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    <i class="mr-2 fas fa-sync-alt"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Products Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/10"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                    <i class="text-xl text-white fas fa-boxes"></i>
                </div>
                <div class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900/50">
                    Total
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $products->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Safe Stock Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-green-600/10"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                    <i class="text-xl text-white fas fa-check-circle"></i>
                </div>
                <div class="px-3 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full dark:text-green-400 dark:bg-green-900/50">
                    Aman
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Aman</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stockSummary['safe'] }}</p>
            </div>
        </div>
    </div>

    <!-- Low Stock Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-yellow-600/10"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl">
                    <i class="text-xl text-white fas fa-exclamation-triangle"></i>
                </div>
                <div class="px-3 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full dark:text-yellow-400 dark:bg-yellow-900/50">
                    Menipis
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Menipis</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stockSummary['low'] }}</p>
            </div>
        </div>
    </div>

    <!-- Out of Stock Card -->
    <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-red-600/10"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-red-500 to-red-600 rounded-xl">
                    <i class="text-xl text-white fas fa-times-circle"></i>
                </div>
                <div class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full dark:text-red-400 dark:bg-red-900/50">
                    Habis
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Habis</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stockSummary['out'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <!-- Enhanced Table Header -->
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3">Produk</th>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-center">Stok Saat Ini</th>
                    <th scope="col" class="px-6 py-3 text-center">Stok Minimum</th>
                    <th scope="col" class="px-6 py-3 text-center">Selisih</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                    @php
                        $difference = $product->current_stock - $product->min_stock;
                    @endphp
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <div class="flex items-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-8 h-8 mr-3 rounded-md">
                                @endif
                                <div>
                                    <div>{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-center">{{ $product->current_stock }} {{ $product->unit }}</td>
                        <td class="px-6 py-4 text-center">{{ $product->min_stock }} {{ $product->unit }}</td>
                        <td class="px-6 py-4 text-center font-medium {{ $difference < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ $difference > 0 ? '+' : '' }}{{ $difference }} {{ $product->unit }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->current_stock <= 0)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                                    <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span>
                                    Stok Habis
                                </span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900/50 dark:text-yellow-300">
                                    <span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span>
                                    Stok Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                    <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span>
                                    Stok Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="mb-2 fas fa-box-open fa-2x"></i>
                            <p>Tidak ada data stok ditemukan.</p>
                            @if(request()->anyFilled(['category_id', 'stock_status']))
                                <a href="{{ route('admin.reports.stock') }}" class="inline-flex items-center mt-2 text-blue-600 dark:text-blue-400 hover:underline">
                                    <i class="mr-1 fas fa-sync-alt"></i> Reset filter
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($products->hasPages())
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
