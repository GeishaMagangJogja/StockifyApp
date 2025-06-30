@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
    <div class="mb-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
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
                        <a href="{{ route('admin.products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Produk</a>
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

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h1>
    </div>

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Form Produk</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi semua informasi yang diperlukan untuk menambahkan produk baru</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Informasi Dasar -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 dark:border-red-500 @enderror"
                               placeholder="Contoh: Laptop ASUS ROG">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="sku" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Kode SKU <span class="text-red-500">*</span>
                            </label>
                            <button type="button" id="generateSkuBtn" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Generate Otomatis
                            </button>
                        </div>
                        <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('sku') border-red-500 dark:border-red-500 @enderror"
                               placeholder="Akan digenerate otomatis">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id" name="category_id" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('category_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Supplier
                        </label>
                        <select id="supplier_id" name="supplier_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('supplier_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Harga & Stok -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="purchase_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Harga Beli <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="hidden" id="purchase_price_raw" name="purchase_price" value="{{ old('purchase_price', '0') }}">
                                <input type="text" id="purchase_price_display" value="{{ old('purchase_price', '0') }}" required
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('purchase_price') border-red-500 dark:border-red-500 @enderror">
                            </div>
                            @error('purchase_price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Harga Jual <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                </div>
                                <input type="hidden" id="selling_price_raw" name="selling_price" value="{{ old('selling_price', '0') }}">
                                <input type="text" id="selling_price_display" value="{{ old('selling_price', '0') }}" required
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('selling_price') border-red-500 dark:border-red-500 @enderror">
                            </div>
                            @error('selling_price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="current_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Stok Awal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="current_stock" name="current_stock" value="{{ old('current_stock', '0') }}" min="0" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('current_stock') border-red-500 dark:border-red-500 @enderror">
                            @error('current_stock')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="minimum_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Stok Minimum <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', '0') }}" min="0" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('minimum_stock') border-red-500 dark:border-red-500 @enderror">
                            @error('minimum_stock')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select id="unit" name="unit" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('unit') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Satuan</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Unit</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                            <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="dus" {{ old('unit') == 'dus' ? 'selected' : '' }}>Dus</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Gambar Produk -->
            <div class="mt-6">
                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Gambar Produk
                </label>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img id="imagePreview" class="object-cover w-24 h-24 rounded-lg" src="https://via.placeholder.com/150?text=No+Image" alt="Preview gambar">
                        <button type="button" id="removeImageBtn" class="absolute p-1 text-white bg-red-500 rounded-full -top-2 -right-2 hover:bg-red-600" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden">
                        <label for="image" class="cursor-pointer">
                            <div class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                Pilih Gambar
                            </div>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Deskripsi Produk
                </label>
                <textarea id="description" name="description" rows="4"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('description') border-red-500 dark:border-red-500 @enderror"
                          placeholder="Deskripsi lengkap produk...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end mt-8 space-x-3">
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="mr-1 fas fa-save"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Currency formatting functions
            function formatCurrencyDisplay(value) {
                return parseInt(value || 0).toLocaleString('id-ID');
            }

            function unformatCurrency(value) {
                return value.replace(/[^0-9]/g, '') || '0';
            }

            // Format currency inputs
            const currencyInputs = [
                { display: 'purchase_price_display', raw: 'purchase_price_raw' },
                { display: 'selling_price_display', raw: 'selling_price_raw' }
            ];

            currencyInputs.forEach(config => {
                const displayInput = document.getElementById(config.display);
                const rawInput = document.getElementById(config.raw);

                if (displayInput && rawInput) {
                    // Format initial value
                    if (rawInput.value && rawInput.value !== '0') {
                        displayInput.value = formatCurrencyDisplay(rawInput.value);
                    }

                    // Add event listener for display input
                    displayInput.addEventListener('input', function(e) {
                        const cursorPosition = e.target.selectionStart;
                        const rawValue = unformatCurrency(this.value);

                        // Update raw value
                        rawInput.value = rawValue;

                        // Format display value
                        this.value = formatCurrencyDisplay(rawValue);

                        // Restore cursor position (approximate)
                        const newPosition = Math.min(cursorPosition, this.value.length);
                        this.setSelectionRange(newPosition, newPosition);
                    });

                    // Sync on blur to ensure consistency
                    displayInput.addEventListener('blur', function() {
                        const rawValue = unformatCurrency(this.value);
                        rawInput.value = rawValue;
                        this.value = formatCurrencyDisplay(rawValue);
                    });
                }
            });

            // Image preview functionality
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const removeImageBtn = document.getElementById('removeImageBtn');

            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        // Validate file size (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar. Maksimal 2MB.');
                            this.value = '';
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            removeImageBtn.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    }
                });

                removeImageBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    imagePreview.src = 'https://via.placeholder.com/150?text=No+Image';
                    this.style.display = 'none';
                });
            }

            // Auto-generate SKU from product name
            const nameInput = document.getElementById('name');
            const skuInput = document.getElementById('sku');
            const generateSkuBtn = document.getElementById('generateSkuBtn');

            if (nameInput && skuInput && generateSkuBtn) {
                // Auto-generate when typing product name (with debounce)
                let debounceTimer;
                nameInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        if (this.value.length >= 3 && !skuInput.value) {
                            generateSKU(this.value);
                        }
                    }, 500);
                });

                // Manual generate button
                generateSkuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (nameInput.value.length >= 3) {
                        generateSKU(nameInput.value);
                    } else {
                        alert('Nama produk minimal 3 karakter');
                        nameInput.focus();
                    }
                });

                function generateSKU(productName) {
                    // Generate SKU from product name
                    const words = productName.trim().split(/\s+/);
                    let prefix = '';

                    if (words.length >= 2) {
                        // Take first letter of first two words
                        prefix = words[0].charAt(0) + words[1].charAt(0);
                    } else {
                        // Take first 2-3 characters of single word
                        prefix = words[0].substring(0, Math.min(3, words[0].length));
                    }

                    prefix = prefix.toUpperCase().replace(/[^A-Z]/g, '');

                    // Add random number
                    const randomNum = Math.floor(1000 + Math.random() * 9000);
                    const timestamp = Date.now().toString().slice(-3);

                    skuInput.value = `${prefix}-${randomNum}${timestamp}`;
                }
            }

                    // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // [Kode validasi yang sudah ada...]

                    // Validate stock
                    const currentStock = parseInt(document.getElementById('current_stock').value);
                    const minimumStock = parseInt(document.getElementById('minimum_stock').value);

                    if (currentStock < 0 || minimumStock < 0) {
                        e.preventDefault();
                        alert('Stok tidak boleh bernilai negatif.');
                        return false;
                    }

                    // Validasi tambahan: stok minimum tidak boleh lebih besar dari stok awal
                    if (minimumStock > currentStock) {
                        e.preventDefault();
                        alert('Stok minimum tidak boleh lebih besar dari stok awal.');
                        document.getElementById('minimum_stock').focus();
                        return false;
                    }
                });
            }

            // Tambahkan event listener untuk memastikan harga jual > harga beli
            const sellingPriceDisplay = document.getElementById('selling_price_display');
            if (sellingPriceDisplay) {
                sellingPriceDisplay.addEventListener('blur', function() {
                    const purchasePrice = parseInt(document.getElementById('purchase_price_raw').value);
                    const sellingPrice = parseInt(document.getElementById('selling_price_raw').value);

                    if (sellingPrice <= purchasePrice) {
                        alert('Harga jual harus lebih besar dari harga beli.');
                        this.focus();
                    }
                });
            }

            // Tambahkan loading state saat form submit
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
                }
            });
        });
    </script>
@endpush
