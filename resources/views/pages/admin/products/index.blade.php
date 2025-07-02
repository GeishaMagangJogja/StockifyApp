@extends('layouts.dashboard')

@section('title', 'Manajemen Produk')

@section('content')
    <!-- Header Section with Glass Effect -->
    <div class="relative mb-8 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl backdrop-blur-sm"></div>
        <div class="relative p-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center mb-4 space-x-2 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-3 py-1 text-gray-600 transition-all duration-200 rounded-lg hover:text-blue-600 hover:bg-white/50 dark:text-gray-400 dark:hover:bg-gray-800/50">
                    <i class="mr-2 fas fa-home"></i>Dashboard
                </a>
                <i class="text-gray-400 fas fa-chevron-right"></i>
                <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                    <i class="mr-2 fas fa-boxes"></i>Produk
                </span>
            </nav>

            <!-- Title Section -->
            <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-boxes dark:text-blue-400"></i>
                        Manajemen Produk
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Kelola dan organisir produk dalam sistem gudang Anda
                    </p>
                </div>
                <a href="{{ route('admin.products.create') }}"
                   class="group relative inline-flex items-center px-6 py-3 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                    <i class="mr-2 fas fa-plus"></i>
                    <span class="font-medium">Tambah Produk</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
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

        <!-- In Stock Products Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-green-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                        <i class="text-xl text-white fas fa-check-circle"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full dark:text-green-400 dark:bg-green-900/50">
                        Stok
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Tersedia</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $stockStats['in_stock'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Low Stock Products Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-amber-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl">
                        <i class="text-xl text-white fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium rounded-full text-amber-600 bg-amber-100 dark:text-amber-400 dark:bg-amber-900/50">
                        Perhatian
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Rendah</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $stockStats['low_stock'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Out of Stock Products Card -->
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
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $stockStats['out_of_stock'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Main Content Card -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <div class="p-6">
                <div class="flex flex-col justify-between space-y-6 lg:flex-row lg:items-center lg:space-y-0">
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-list dark:text-blue-400"></i>
                            Daftar Produk
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Kelola semua produk Anda dengan mudah
                        </p>
                    </div>

                    <!-- Export/Import Buttons -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <!-- Export Button -->
                        <a href="{{ route('admin.products.export', request()->query()) }}"
                           class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="mr-2 fas fa-file-export"></i>
                            Export
                        </a>

                        <!-- Download Template -->
                        <a href="{{ route('admin.products.export-template') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="mr-2 fas fa-file-download"></i>
                            Template Import
                        </a>

                        <!-- Import Button -->
                        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                                class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="mr-2 fas fa-file-import"></i>
                            Import
                        </button>
                    </div>

                    <!-- Import Modal -->
                    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4 dark:bg-gray-800">
                                        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">
                                            <i class="mr-2 fas fa-file-import"></i> Import Produk
                                        </h3>

                                        <div class="mb-4">
                                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Pilih File Excel
                                            </label>
                                            <input type="file" name="file" accept=".xlsx,.xls" required
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Format file harus .xlsx atau .xls (Max: 5MB)
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                                        <button type="submit"
                                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            <i class="mr-2 fas fa-upload"></i> Upload & Import
                                        </button>
                                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500">
                                            <i class="mr-2 fas fa-times"></i> Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-6">
            @if(session('import_errors'))
                <div class="p-4 mb-6 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg dark:bg-yellow-800/30 dark:border-yellow-600">
                    <div class="flex items-center">
                        <i class="mr-3 text-yellow-700 fas fa-exclamation-triangle dark:text-yellow-400"></i>
                        <div>
                            <p class="font-medium text-yellow-700 dark:text-yellow-400">Import Selesai dengan Beberapa Error</p>
                            <ul class="mt-2 text-sm text-yellow-600 dark:text-yellow-300">
                                @foreach(session('import_errors') as $error)
                                    <li class="py-1">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if($products->count() > 0)
                <div class="overflow-hidden border border-gray-200 rounded-xl dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-box"></i>Produk
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-tag"></i>Kategori
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-truck"></i>Supplier
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-money-bill-wave"></i>Harga Beli
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-money-bill-wave"></i>Harga Jual
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-boxes"></i>Stok
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-info-circle"></i>Status
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-cog"></i>Aksi
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($products as $product)
                                    <tr class="transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="flex items-center justify-center w-12 h-12 overflow-hidden transition-shadow duration-200 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl group-hover:shadow-xl">
                                                        <img class="object-cover w-full h-full"
                                                             src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}"
                                                             alt="{{ $product->name }}">
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        <a href="{{ route('admin.products.show', $product) }}" class="hover:underline">
                                                            {{ $product->name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        SKU: <span class="font-mono">{{ $product->sku }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">
                                                {{ $product->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $product->supplier->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $product->formatted_purchase_price }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $product->formatted_selling_price }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Min: {{ number_format($product->min_stock, 0) }} {{ $product->unit }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $currentStock = $product->current_stock;
                                                $minStock = $product->min_stock;
                                            @endphp

                                            @if($currentStock <= 0)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                                                    <i class="mr-1 fas fa-times-circle"></i> Habis
                                                </span>
                                            @elseif($currentStock <= $minStock)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full text-amber-800 bg-amber-100 dark:bg-amber-900/50 dark:text-amber-300">
                                                    <i class="mr-1 fas fa-exclamation-triangle"></i> Rendah
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                                    <i class="mr-1 fas fa-check-circle"></i> Normal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-1">
                                                <a href="{{ route('admin.products.show', $product) }}"
                                                   class="inline-flex items-center justify-center text-blue-600 transition-all duration-200 rounded-lg w-9 h-9 hover:bg-blue-50 dark:hover:bg-gray-700 dark:text-blue-400 hover:scale-110"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="inline-flex items-center justify-center transition-all duration-200 rounded-lg w-9 h-9 text-amber-600 hover:bg-amber-50 dark:hover:bg-gray-700 dark:text-amber-400 hover:scale-110"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.products.confirm-delete', $product) }}"
                                                   class="inline-flex items-center justify-center text-red-600 transition-all duration-200 rounded-lg w-9 h-9 hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400 hover:scale-110"
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                <div class="mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="py-16 text-center">
                    <div class="relative w-32 h-32 mx-auto mb-8">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800"></div>
                        <div class="relative flex items-center justify-center w-full h-full">
                            <i class="text-5xl text-gray-400 fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Belum ada produk</h3>
                        <p class="max-w-md mx-auto text-gray-500 dark:text-gray-400">
                            Mulai mengelola inventaris Anda dengan menambahkan produk pertama. Produk akan membantu Anda melacak stok dan penjualan.
                        </p>
                        <div class="pt-4">
                            <a href="{{ route('admin.products.create') }}"
                               class="inline-flex items-center px-8 py-4 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="mr-3 fas fa-plus"></i>
                                <span class="font-semibold">Tambah Produk Pertama</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
