@extends('layouts.dashboard')

@section('title', 'Laporan Stok Barang')

@section('content')
{{-- Unified Card Layout --}}
<div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">

    {{-- Card Header: Title & Filters --}}
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-start justify-between sm:flex-row sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Laporan Stok Barang</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Analisis stok produk berdasarkan kategori dan status stok.
                    @if(request('category_id'))
                        <span class="font-medium">Filter: {{ $categories->find(request('category_id'))->name }}</span>
                    @endif
                </p>
            </div>

            {{-- Filter Form --}}
            <form method="GET" class="flex flex-col items-start gap-4 mt-4 sm:mt-0 sm:flex-row sm:items-center">
                <div class="flex items-center gap-4">
                    <select name="category_id" class="px-3 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-md shadow-sm w-52 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="stock_status" class="w-40 px-3 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-md shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
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

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-md shadow-sm hover:bg-blue-700">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.reports.stock') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 gap-4 p-6 border-b border-gray-200 md:grid-cols-4 dark:border-gray-700">
        <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/30">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Total Produk</h3>
            <p class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-100">{{ $products->total() }}</p>
        </div>
        <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/30">
            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Stok Aman</h3>
            <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-100">{{ $stockSummary['safe'] }}</p>
        </div>
        <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/30">
            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Stok Menipis</h3>
            <p class="mt-1 text-2xl font-semibold text-yellow-600 dark:text-yellow-100">{{ $stockSummary['low'] }}</p>
        </div>
        <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/30">
            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Stok Habis</h3>
            <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-100">{{ $stockSummary['out'] }}</p>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            {{-- Enhanced Table Header --}}
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-left text-gray-600 uppercase dark:text-gray-300">Produk</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-left text-gray-600 uppercase dark:text-gray-300">Kategori</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300">Stok Saat Ini</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300">Stok Minimum</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300">Selisih</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider text-left text-gray-600 uppercase dark:text-gray-300">Status</th>
                </tr>
            </thead>
            {{-- Zebra-striping and hover effect --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                    @php
                        $difference = $product->current_stock - $product->min_stock;
                    @endphp
                    <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <div class="flex items-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-8 h-8 mr-3 rounded-md">
                                @endif
                                <div>
                                    <div class="font-medium">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-center text-gray-900 whitespace-nowrap dark:text-white">{{ $product->current_stock }} {{ $product->unit }}</td>
                        <td class="px-6 py-4 text-center text-gray-500 whitespace-nowrap dark:text-gray-300">{{ $product->min_stock }} {{ $product->unit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium {{ $difference < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ $difference > 0 ? '+' : '' }}{{ $difference }} {{ $product->unit }}
                        </td>
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
                        <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="mb-2 fas fa-box-open fa-2x"></i>
                            <p>Tidak ada data stok ditemukan.</p>
                            @if(request()->anyFilled(['category_id', 'stock_status']))
                                <a href="{{ route('admin.reports.stock') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Reset filter</a>
                            @endif
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
