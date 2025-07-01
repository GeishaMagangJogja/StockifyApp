@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 transition-colors hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 transition-colors hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Produk</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit Produk</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Produk: {{ $product->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi produk sesuai kebutuhan</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Status Badge -->
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($product->current_stock <= 0) bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                    @elseif($product->current_stock <= $product->minimum_stock) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                    @else bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300 @endif">
                    Stok: {{ $product->current_stock }} {{ $product->unit }}
                </span>
                <!-- Quick Actions -->
                <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700" onclick="window.print()">
                    <i class="mr-1 fas fa-print"></i>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
            <div class="flex items-center mb-2">
                <i class="mr-2 fas fa-exclamation-triangle"></i>
                <span class="font-medium">Terdapat kesalahan dalam form:</span>
            </div>
            <ul class="space-y-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="flex items-center text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="mr-2 text-blue-600 fas fa-edit dark:text-blue-400"></i>
                        Form Edit Produk
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui field yang diperlukan</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                        <i class="mr-1 fas fa-info-circle"></i>
                        Terakhir diupdate: {{ $product->updated_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6" id="productForm" novalidate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Left Column - Basic Information -->
                <div class="space-y-6 lg:col-span-8">
                    <!-- Section: Product Information -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                        <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-box dark:text-blue-400"></i>
                            Informasi Produk
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Product Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('name') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="Contoh: Laptop ASUS ROG Strix G15" autofocus>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Kode SKU <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('sku') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="SKU produk">
                                @error('sku')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <select id="unit" name="unit" required
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('unit') border-red-500 dark:border-red-500 @enderror">
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pcs (Pieces)</option>
                                    <option value="unit" {{ old('unit', $product->unit) == 'unit' ? 'selected' : '' }}>Unit</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                    <option value="gram" {{ old('unit', $product->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="dus" {{ old('unit', $product->unit) == 'dus' ? 'selected' : '' }}>Dus</option>
                                </select>
                                @error('unit')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id" name="category_id" required
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('category_id') border-red-500 dark:border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Supplier
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('supplier_id') border-red-500 dark:border-red-500 @enderror">
                                    <option value="">Pilih Supplier (Opsional)</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Price & Stock -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                        <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                            <i class="mr-2 text-green-600 fas fa-dollar-sign dark:text-green-400"></i>
                            Harga & Stok
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Purchase Price -->
                            <div>
                                <label for="purchase_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Harga Beli <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="font-medium text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="hidden" id="purchase_price_raw" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}">
                                    <input type="text" id="purchase_price_display" value="{{ number_format(old('purchase_price', $product->purchase_price), 0, ',', '.') }}" required
                                           class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('purchase_price') border-red-500 dark:border-red-500 @enderror"
                                           placeholder="0">
                                </div>
                                @error('purchase_price')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Selling Price -->
                            <div>
                                <label for="selling_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Harga Jual <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="font-medium text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="hidden" id="selling_price_raw" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}">
                                    <input type="text" id="selling_price_display" value="{{ number_format(old('selling_price', $product->selling_price), 0, ',', '.') }}" required
                                           class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('selling_price') border-red-500 dark:border-red-500 @enderror"
                                           placeholder="0">
                                </div>
                                @error('selling_price')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <!-- Profit Margin Display -->
                                <div id="profitMargin" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Margin: </span>
                                    <span id="marginAmount" class="text-green-600 dark:text-green-400">
                                        Rp{{ number_format($product->selling_price - $product->purchase_price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-gray-500">(
                                        <span id="marginPercent">
                                            {{ $product->purchase_price > 0 ? number_format((($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100, 2) : 0 }}
                                        </span>%)
                                    </span>
                                </div>
                            </div>

                            <!-- Minimum Stock -->
                            <div>
                                <label for="min_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Stok Minimum <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('min_stock') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="0">
                                @error('min_stock')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sistem akan memberikan peringatan jika stok mencapai batas ini</p>
                            </div>


                        </div>
                    </div>

                    <!-- Description -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                        <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                            <i class="mr-2 text-purple-600 fas fa-align-left dark:text-purple-400"></i>
                            Deskripsi Produk
                        </h3>
                        <div>
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Deskripsi Detail
                            </label>
                            <textarea id="description" name="description" rows="5"
                                      class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('description') border-red-500 dark:border-red-500 @enderror"
                                      placeholder="Tuliskan deskripsi lengkap tentang produk ini, termasuk spesifikasi, fitur, dan informasi penting lainnya...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Deskripsi yang detail akan membantu dalam identifikasi dan pencarian produk</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image Upload -->
                <div class="lg:col-span-4">
                    <div class="sticky top-6">
                        <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                            <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                                <i class="mr-2 text-indigo-600 fas fa-image dark:text-indigo-400"></i>
                                Gambar Produk
                            </h3>

                            <!-- Image Preview -->
                            <div class="mb-4">
                                <div class="relative group">
                                    <img id="imagePreview" class="object-cover w-full h-48 transition-all border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-600 group-hover:border-blue-400"
                                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                                    alt="Preview gambar">
                                    <button type="button" id="removeImageBtn"
                                            class="absolute flex items-center justify-center p-2 text-white transition-colors bg-red-500 rounded-full shadow-lg -top-2 -right-2 hover:bg-red-600"
                                            style="{{ !$product->image || str_contains($product->image, 'placeholder') ? 'display: none;' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <div>
                                <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                                <input type="file" id="image" name="image" accept="image/*" class="hidden">
                                <label for="image" class="cursor-pointer">
                                    <div class="flex flex-col items-center justify-center w-full px-4 py-6 text-sm font-medium text-gray-700 transition-all bg-white border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 hover:border-blue-400 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-blue-500">
                                        <i class="mb-2 text-2xl text-blue-500 fas fa-cloud-upload-alt"></i>
                                        <span class="font-medium">Klik untuk pilih gambar</span>
                                        <span class="mt-1 text-xs text-gray-500 dark:text-gray-400">atau drag & drop file di sini</span>
                                    </div>
                                </label>
                                <div class="mt-3 space-y-1 text-xs text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <i class="mr-1 text-green-500 fas fa-check-circle"></i>
                                        Format: JPG, PNG, GIF
                                    </div>
                                    <div class="flex items-center">
                                        <i class="mr-1 text-green-500 fas fa-check-circle"></i>
                                        Maksimal: 2MB
                                    </div>
                                    <div class="flex items-center">
                                        <i class="mr-1 text-blue-500 fas fa-info-circle"></i>
                                        Rekomendasi: 800x600px
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Product Stats -->
                        <div class="p-6 mt-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                            <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                                <i class="mr-2 text-blue-600 fas fa-chart-bar dark:text-blue-400"></i>
                                Statistik Produk
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</h3>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $product->stockTransactions()->count() }}
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Masuk</h3>
                                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                                        {{ $product->stockTransactions()->where('type', 'Masuk')->sum('quantity') }}
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Keluar</h3>
                                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                                        {{ $product->stockTransactions()->where('type', 'Keluar')->sum('quantity') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6">
                            <a href="{{ route('admin.products.show', $product->id) }}"
                               class="flex items-center justify-center w-full px-4 py-3 mb-3 text-sm font-medium text-gray-900 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                                <i class="mr-2 fas fa-eye"></i>
                                Lihat Detail Produk
                            </a>
                            <button type="button" id="deleteProductBtn"
                                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700">
                                <i class="mr-2 fas fa-trash-alt"></i>
                                Hapus Produk
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle"></i>
                    <span>Semua field bertanda (*) wajib diisi</span>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        <i class="mr-2 fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors">
                        <i class="mr-2 fas fa-save"></i>
                        Update Produk
                    </button>
                </div>
            </div>
        </form>

        <form id="deleteProductForm" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------------------------------------------
    // LOGIKA UNTUK GAMBAR
    // -----------------------------------------------------------------
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const removeImageInput = document.getElementById('removeImageInput');
    const placeholderSrc = 'https://via.placeholder.com/300x200?text=No+Image';
    let originalImageSrc = imagePreview.src;
    // Pastikan semua elemen ada sebelum memasang listener
    if (imageInput && imagePreview && removeImageBtn && removeImageInput) {

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                removeImageInput.value = '0'; // <-- Reset jika file baru dipilih
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
                removeImageBtn.style.display = 'flex';
            }
        });

        // Listener saat tombol Hapus (X) diklik
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.src = placeholderSrc;
            removeImageBtn.style.display = 'none';
            removeImageInput.value = '1'; // <-- [PENTING] Set nilai menjadi 1
        });
    }

    // -----------------------------------------------------------------
    // LOGIKA UNTUK HARGA (Tidak Berubah)
    // -----------------------------------------------------------------
    const purchasePriceInput = document.getElementById('purchase_price_display');
    const sellingPriceInput = document.getElementById('selling_price_display');
    const purchasePriceRaw = document.getElementById('purchase_price_raw');
    const sellingPriceRaw = document.getElementById('selling_price_raw');
    const profitMarginEl = document.getElementById('profitMargin');

    function formatCurrency(inputEl, rawInputEl) {
        let value = inputEl.value.replace(/[^0-9]/g, '');
        rawInputEl.value = value || '0';
        inputEl.value = new Intl.NumberFormat('id-ID').format(value || 0);
    }

    function calculateProfitMargin() {
        if (!profitMarginEl) return;

        const purchase = parseInt(purchasePriceRaw.value) || 0;
        const selling = parseInt(sellingPriceRaw.value) || 0;

        if (purchase > 0 && selling >= purchase) {
            const margin = selling - purchase;
            const percent = ((margin / purchase) * 100).toFixed(2);

            profitMarginEl.querySelector('#marginAmount').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(margin);
            profitMarginEl.querySelector('#marginPercent').textContent = percent;
            profitMarginEl.style.display = 'block';
        } else {
            profitMarginEl.style.display = 'none';
        }
    }

    if (purchasePriceInput && sellingPriceInput) {
        purchasePriceInput.addEventListener('input', () => {
            formatCurrency(purchasePriceInput, purchasePriceRaw);
            calculateProfitMargin();
        });

        sellingPriceInput.addEventListener('input', () => {
            formatCurrency(sellingPriceInput, sellingPriceRaw);
            calculateProfitMargin();
        });

        // Inisialisasi saat halaman dimuat
        calculateProfitMargin();
    }
});
</script>
@endpush
