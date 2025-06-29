@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.products.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Produk: {{ $product->name }}</h1>
            </div>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Perbarui detail produk di bawah ini.</p>
        </div>

        {{-- GANTI ACTION FORM & TAMBAHKAN METHOD PUT --}}
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT') 
            
            {{-- Kolom Utama (Form) --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                    <div class="p-6 space-y-6">
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Terjadi Kesalahan Validasi</p>
                                <ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        {{-- Nama Produk --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            {{-- TAMBAHKAN VALUE DARI $product --}}
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>

                        {{-- SKU & Kategori --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SKU (Kode Produk) <span class="text-red-500">*</span></label>
                                <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier <span class="text-red-500">*</span></label>
                            <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Unit & Stok Minimum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Satuan Unit <span class="text-red-500">*</span></label>
                                <input type="text" id="unit" name="unit" value="{{ old('unit', $product->unit) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stok Minimum <span class="text-red-500">*</span></label>
                                <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                        </div>

                        {{-- Harga Beli & Harga Jual --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Beli <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" step="any" class="w-full pl-8 pr-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Jual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" min="0" step="any" class="w-full pl-8 pr-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi (Opsional)</label>
                            <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Samping --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">Aksi</h3>
                    <div class="mt-6">
                         <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center justify-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection