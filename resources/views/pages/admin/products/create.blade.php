@extends('layouts.dashboard')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.products.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Produk Baru</h1>
            </div>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Isi detail di bawah ini untuk mendaftarkan produk baru ke dalam sistem.</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            {{-- Kolom Utama (Form) --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                    <div class="p-6 space-y-6">
                        {{-- Notifikasi Error Validasi --}}
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Terjadi Kesalahan Validasi</p>
                                <ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        {{-- Nama Produk --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Kabel USB Type-C" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>

                        {{-- Kode Produk & Kategori --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Produk <span class="text-red-500">*</span></label>
                                <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Contoh: KBL-USB-C-01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier <span class="text-red-500">*</span></label>
                            <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                                <option value="">-- Tidak ada supplier spesifik --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Unit & Stok Minimum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Satuan Unit <span class="text-red-500">*</span></label>
                                <input type="text" id="unit" name="unit" value="{{ old('unit') }}" placeholder="Contoh: Pcs, Box, Meter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stok Minimum <span class="text-red-500">*</span></label>
                                <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                        </div>

                        {{-- Harga Beli & Harga Jual --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Beli <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" placeholder="0" min="0" step="any" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Jual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" placeholder="0" min="0" step="any" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi (Opsional)</label>
                            <textarea id="description" name="description" rows="4" placeholder="Deskripsi singkat mengenai produk, spesifikasi, dll." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Samping (Info & Aksi) --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">Informasi</h3>
                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-4">
                        <p>
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Pastikan **Kode Produk** bersifat unik dan belum pernah digunakan sebelumnya.
                        </p>
                        <p>
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            **Stok Minimum** akan digunakan sebagai ambang batas untuk notifikasi "Stok Rendah".
                        </p>
                         <p>
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Stok awal untuk produk baru akan diatur ke **0**. Anda harus menambahkan stok melalui menu **Barang Masuk**.
                        </p>
                    </div>
                    <div class="mt-8">
                         <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection