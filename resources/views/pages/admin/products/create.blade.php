@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Produk</a>
            <span>/</span>
            <span>Tambah Produk</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h1>
    </div>

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk</h2>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama Produk -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span>SKU</span>
                        <button type="button" id="generateSkuBtn"
                                class="px-2 py-1 text-xs text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded">
                            Generate Auto
                        </button>
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('sku') border-red-500 @enderror"
                           placeholder="Akan di-generate otomatis dari nama produk">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">SKU akan di-generate otomatis saat mengetik nama produk, atau klik "Generate Auto"</p>
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select id="category_id" name="category_id"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('category_id') border-red-500 @enderror">
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

                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                    <select id="supplier_id" name="supplier_id"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('supplier_id') border-red-500 @enderror">
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

                <!-- Harga Beli -->
                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli</label>
                    <input type="text" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', '0') }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('purchase_price') border-red-500 @enderror">
                    @error('purchase_price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Jual -->
                <div>
                    <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                    <input type="text" id="selling_price" name="selling_price" value="{{ old('selling_price', '0') }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('selling_price') border-red-500 @enderror">
                    @error('selling_price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok Awal -->
                <div>
                    <label for="initial_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Awal</label>
                    <input type="text" id="initial_stock" name="initial_stock" value="{{ old('initial_stock', '0') }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('initial_stock') border-red-500 @enderror">
                    @error('initial_stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Stok -->
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="text" id="min_stock" name="min_stock" value="{{ old('min_stock', '0') }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('min_stock') border-red-500 @enderror">
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Produk -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Produk</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                              class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Produk Aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Centang untuk mengaktifkan produk</p>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end pt-6 space-x-3 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="mr-2 fas fa-save"></i>Simpan Produk
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let skuGenerationTimeout;

        // Auto-generate SKU when typing product name
        document.getElementById('name').addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(skuGenerationTimeout);

            // Set timeout to avoid too many requests while typing
            skuGenerationTimeout = setTimeout(() => {
                if (this.value.trim().length >= 3) {
                    generateSkuFromName(this.value.trim());
                } else {
                    document.getElementById('sku').value = '';
                }
            }, 500); // Wait 500ms after user stops typing
        });

        // Manual generate SKU button
        document.getElementById('generateSkuBtn').addEventListener('click', function() {
            const nameInput = document.getElementById('name');
            if (nameInput.value.trim()) {
                generateSkuFromName(nameInput.value.trim());
            } else {
                alert('Silakan isi nama produk terlebih dahulu');
                nameInput.focus();
            }
        });

        // Function to generate SKU from product name
        function generateSkuFromName(productName) {
            const skuInput = document.getElementById('sku');

            // Show loading state
            skuInput.value = 'Generating...';
            skuInput.disabled = true;

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]') ||
                         document.querySelector('input[name="_token"]');

            const csrfToken = token ? (token.getAttribute('content') || token.value) : '';

            // Make request to generate SKU
            fetch('{{ route("admin.products.generate-sku") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: productName
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.sku) {
                    skuInput.value = data.sku;
                } else {
                    throw new Error('No SKU returned');
                }
            })
            .catch(error => {
                console.error('Error generating SKU:', error);

                // Fallback: generate simple SKU locally
                const fallbackSku = generateFallbackSku(productName);
                skuInput.value = fallbackSku;

                // Show warning
                console.warn('Using fallback SKU generation');
            })
            .finally(() => {
                skuInput.disabled = false;
            });
        }

        // Fallback SKU generation (client-side)
        function generateFallbackSku(productName) {
            // Take first 3 letters of the product name
            const prefix = productName.replace(/[^A-Za-z]/g, '').substring(0, 3).toUpperCase();
            const paddedPrefix = prefix.padEnd(3, 'X');

            // Generate random 4-digit number
            const randomNum = Math.floor(1000 + Math.random() * 9000);

            return paddedPrefix + '-' + randomNum;
        }

        // Format currency input
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value === '') value = '0';

            // Format with thousands separator
            const formatted = parseInt(value, 10).toLocaleString('id-ID');
            input.value = formatted;
        }

        // Format number input (for stock fields)
        function formatNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        // Initialize event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Format currency fields
            const currencyFields = ['purchase_price', 'selling_price'];
            currencyFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', () => formatCurrency(field));
                    formatCurrency(field); // Format initial value
                }
            });

            // Format number fields
            const numberFields = ['initial_stock', 'min_stock'];
            numberFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', () => formatNumber(field));
                }
            });

            // Auto-generate SKU if name is already filled (for when there are validation errors)
            const nameInput = document.getElementById('name');
            if (nameInput.value.trim()) {
                generateSkuFromName(nameInput.value.trim());
            }
        });
    </script>
@endpush
