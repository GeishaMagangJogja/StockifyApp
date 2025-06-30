@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors">
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
                        <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white transition-colors">Produk</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Tambah Baru</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi semua informasi yang diperlukan untuk menambahkan produk baru ke dalam sistem</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Quick Actions -->
                <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-400 dark:border-red-800">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Terdapat kesalahan dalam form:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-plus-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                        Form Data Produk
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lengkapi semua field yang bertanda (*) wajib diisi</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Form Baru
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="productForm">
            @csrf

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Left Column - Basic Information -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Section: Product Information -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-box mr-2 text-blue-600 dark:text-blue-400"></i>
                            Informasi Produk
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Product Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('name') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="Contoh: Laptop ASUS ROG Strix G15" autofocus>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="sku" class="block text-sm font-medium text-gray-900 dark:text-white">
                                        Kode SKU <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" id="generateSkuBtn" class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors">
                                        <i class="fas fa-magic mr-1"></i>
                                        Generate
                                    </button>
                                </div>
                                <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('sku') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="SKU akan digenerate otomatis">
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
                                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs (Pieces)</option>
                                    <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Unit</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="dus" {{ old('unit') == 'dus' ? 'selected' : '' }}>Dus</option>
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
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-dollar-sign mr-2 text-green-600 dark:text-green-400"></i>
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
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                                    </div>
                                    <input type="hidden" id="purchase_price_raw" name="purchase_price" value="{{ old('purchase_price', '0') }}">
                                    <input type="text" id="purchase_price_display" value="{{ old('purchase_price', '0') }}" required
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
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                                    </div>
                                    <input type="hidden" id="selling_price_raw" name="selling_price" value="{{ old('selling_price', '0') }}">
                                    <input type="text" id="selling_price_display" value="{{ old('selling_price', '0') }}" required
                                           class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('selling_price') border-red-500 dark:border-red-500 @enderror"
                                           placeholder="0">
                                </div>
                                @error('selling_price')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <!-- Profit Margin Display -->
                                <div id="profitMargin" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden">
                                    <span class="font-medium">Margin: </span>
                                    <span id="marginAmount" class="text-green-600 dark:text-green-400"></span>
                                    <span class="text-gray-500">(<span id="marginPercent"></span>%)</span>
                                </div>
                            </div>

                            <!-- Current Stock -->
                            <div>
                                <label for="current_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Stok Awal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="current_stock" name="current_stock" value="{{ old('current_stock', '0') }}" min="0" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('current_stock') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="0">
                                @error('current_stock')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Minimum Stock -->
                            <div>
                                <label for="minimum_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Stok Minimum <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', '0') }}" min="0" required
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('minimum_stock') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="0">
                                @error('minimum_stock')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sistem akan memberikan peringatan jika stok mencapai batas ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="p-6 border border-gray-200 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-align-left mr-2 text-purple-600 dark:text-purple-400"></i>
                            Deskripsi Produk
                        </h3>
                        <div>
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Deskripsi Detail
                            </label>
                            <textarea id="description" name="description" rows="5"
                                      class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all @error('description') border-red-500 dark:border-red-500 @enderror"
                                      placeholder="Tuliskan deskripsi lengkap tentang produk ini, termasuk spesifikasi, fitur, dan informasi penting lainnya...">{{ old('description') }}</textarea>
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
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-image mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                Gambar Produk
                            </h3>

                            <!-- Image Preview -->
                            <div class="mb-4">
                                <div class="relative group">
                                    <img id="imagePreview" class="object-cover w-full h-48 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 transition-all group-hover:border-blue-400" src="https://via.placeholder.com/300x200?text=No+Image" alt="Preview gambar">
                                    <button type="button" id="removeImageBtn" class="absolute p-2 text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600 transition-colors shadow-lg" style="display: none;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <div>
                                <input type="file" id="image" name="image" accept="image/*" class="hidden">
                                <label for="image" class="cursor-pointer">
                                    <div class="flex flex-col items-center justify-center w-full px-4 py-6 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 hover:border-blue-400 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-blue-500 transition-all">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2 text-blue-500"></i>
                                        <span class="font-medium">Klik untuk pilih gambar</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">atau drag & drop file di sini</span>
                                    </div>
                                </label>
                                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                        Format: JPG, PNG, GIF
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                        Maksimal: 2MB
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                        Rekomendasi: 800x600px
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2 flex items-center">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Tips Pengisian Form
                            </h4>
                            <ul class="text-xs text-blue-800 dark:text-blue-400 space-y-1">
                                <li>• Gunakan nama produk yang jelas dan deskriptif</li>
                                <li>• Pastikan harga jual lebih tinggi dari harga beli</li>
                                <li>• Set stok minimum untuk mendapat notifikasi</li>
                                <li>• Upload gambar berkualitas untuk identifikasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle"></i>
                    <span>Semua field bertanda (*) wajib diisi</span>
                </div>
                <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Generate SKU
        document.getElementById('generateSkuBtn').addEventListener('click', function() {
            const name = document.getElementById('name').value;
            const category = document.getElementById('category_id').value;

            if (!name) {
                alert('Silakan isi nama produk terlebih dahulu');
                return;
            }

            if (!category) {
                alert('Silakan pilih kategori terlebih dahulu');
                return;
            }

            // Simple SKU generation logic
            const categoryPrefix = document.querySelector(`#category_id option[value="${category}"]`).text.substring(0, 3).toUpperCase();
            const namePrefix = name.substring(0, 3).toUpperCase();
            const randomNum = Math.floor(1000 + Math.random() * 9000);

            const sku = `${categoryPrefix}-${namePrefix}-${randomNum}`;
            document.getElementById('sku').value = sku;
        });

        // Image Preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('imagePreview').src = event.target.result;
                    document.getElementById('removeImageBtn').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove Image
        document.getElementById('removeImageBtn').addEventListener('click', function() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').src = 'https://via.placeholder.com/300x200?text=No+Image';
            this.style.display = 'none';
        });

        // Currency Formatting for Prices
        const purchasePriceInput = document.getElementById('purchase_price_display');
        const sellingPriceInput = document.getElementById('selling_price_display');
        const purchasePriceRaw = document.getElementById('purchase_price_raw');
        const sellingPriceRaw = document.getElementById('selling_price_raw');
        const profitMargin = document.getElementById('profitMargin');

        function formatCurrency(input, rawInput) {
            let value = input.value.replace(/[^0-9]/g, '');
            value = value === '' ? '0' : value;
            rawInput.value = value;
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }

        function calculateProfitMargin() {
            const purchase = parseInt(purchasePriceRaw.value) || 0;
            const selling = parseInt(sellingPriceRaw.value) || 0;

            if (purchase > 0 && selling > 0) {
                const margin = selling - purchase;
                const percent = ((margin / purchase) * 100).toFixed(2);

                document.getElementById('marginAmount').textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(margin);

                document.getElementById('marginPercent').textContent = percent;
                profitMargin.classList.remove('hidden');
            } else {
                profitMargin.classList.add('hidden');
            }
        }

        purchasePriceInput.addEventListener('input', function() {
            formatCurrency(purchasePriceInput, purchasePriceRaw);
            calculateProfitMargin();
        });

        sellingPriceInput.addEventListener('input', function() {
            formatCurrency(sellingPriceInput, sellingPriceRaw);
            calculateProfitMargin();
        });

        // Initialize values on load
        formatCurrency(purchasePriceInput, purchasePriceRaw);
        formatCurrency(sellingPriceInput, sellingPriceRaw);
        calculateProfitMargin();
    </script>
@endpush

