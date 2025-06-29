@extends('layouts.dashboard')

@section('title', 'Daftar Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-200">Daftar Produk</h1>
            <p class="text-gray-400">Lihat semua produk yang terdaftar dalam sistem.</p>
        </div>
        {{-- Tombol "Tambah Produk" dihilangkan karena Manajer read-only --}}
    </div>

    {{-- Filter & Search --}}
    {{-- GANTI dark:bg-dark-primary MENJADI dark:bg-slate-800 --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow mb-6 p-6">
        <form method="GET" action="{{ route('manajergudang.products.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama atau kode produk..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors mr-2">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('manajergudang.products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    {{-- GANTI dark:bg-dark-primary MENJADI dark:bg-slate-800 --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                {{-- GANTI dark:bg-gray-700 MENJADI dark:bg-slate-700 untuk konsistensi --}}
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Stok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Unit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                    @forelse($products as $product)
                        {{-- GANTI dark:hover:bg-slate-700 agar konsisten --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product->code }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                    {{ $product->category->name ?? 'Tidak ada kategori' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex flex-col">
                                    @php
                                        // Anda perlu memastikan relasi 'transactions' di-load untuk performa yang baik
                                        $stock = $product->stockTransactions->where('type', 'in')->sum('quantity') - $product->stockTransactions->where('type', 'out')->sum('quantity');
                                    @endphp
                                    <span class="font-medium">{{ $stock }}</span>
                                    <span class="text-xs text-gray-500">Min: {{ $product->min_stock }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex flex-col">
                                    {{-- PERBAIKAN: Langsung panggil accessor current_stock --}}
                                    <span class="font-medium">{{ $product->current_stock }}</span>
                                    <span class="text-xs text-gray-500">Min: {{ $product->min_stock }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $product->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    // PERBAIKAN: Gunakan accessor yang sama
                                    $currentStock = $product->current_stock;
                                    $minStock = $product->min_stock;
                                @endphp
                                @if($currentStock <= 0)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Habis
                                    </span>
                                @elseif($currentStock <= $minStock)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Stok Rendah
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('manajergudang.products.show', $product) }}"
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data produk ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection