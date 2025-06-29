@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Produk</a>
            <span>/</span>
            <span>Edit Produk</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Produk: {{ $product->name }}</h1>
    </div>

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk</h2>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama Produk -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU <span class="text-red-500">*</span></label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('sku') border-red-500 @enderror">
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
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                    <input type="text" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', number_format($product->purchase_price, 0, ',', '.')) }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('purchase_price') border-red-500 @enderror"
                           oninput="formatCurrency(this)">
                    @error('purchase_price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Jual -->
                <div>
                    <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Jual</label>
                    <input type="text" id="selling_price" name="selling_price" value="{{ old('selling_price', number_format($product->selling_price, 0, ',', '.')) }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('selling_price') border-red-500 @enderror"
                           oninput="formatCurrency(this)">
                    @error('selling_price')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok</label>
                    <input type="text" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('stock') border-red-500 @enderror"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Stok -->
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="text" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required
                           class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('min_stock') border-red-500 @enderror"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Produk -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Produk</label>

                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-32 h-32 rounded">
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="remove_image" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Hapus gambar</span>
                                </label>
                            </div>
                        </div>
                    @endif

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
                              class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
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
                    <i class="mr-2 fas fa-save"></i>Update Produk
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Format currency input
        function formatCurrency(input) {
            // If input is an element, get its value
            const value = typeof input === 'object' ? input.value : input;

            // Remove all non-digit characters
            let num = value.replace(/[^0-9]/g, '');

            // If empty, set to 0
            if (num === '') num = '0';

            // Format with thousands separators
            const formatted = parseInt(num, 10).toLocaleString('id-ID');

            // Update the input value
            if (typeof input === 'object') {
                input.value = formatted;
            }

            return formatted;
        }

        // Initialize formatting on page load
        document.addEventListener('DOMContentLoaded', function() {
            formatCurrency(document.getElementById('purchase_price'));
            formatCurrency(document.getElementById('selling_price'));
        });
    </script>
@endpush
