@extends('layouts.dashboard')

@section('title', 'Catat Barang Masuk')

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockForm', () => ({
            selectedProductId: '{{ old('product_id', request()->get('product_id')) }}' || null,
            quantity: '{{ old('quantity') }}' || 1,
            products: {!! json_encode($products->keyBy('id')) !!},
            init() {
                const selectEl = document.getElementById('product_id');
                if (!selectEl) return;
                const tomselect = new TomSelect(selectEl, {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    // [IMPROVE] Custom rendering untuk menampilkan gambar dan stok
                    render: {
                        option: function(data, escape) {
                            return `<div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 object-cover rounded" src="${escape(data.image_url)}" alt="${escape(data.text)}">
                                        <div>
                                            <div class="font-medium">${escape(data.text)}</div>
                                            <div class="text-xs text-gray-500">Stok: ${escape(data.stock)} ${escape(data.unit)}</div>
                                        </div>
                                    </div>`;
                        },
                        item: function(item, escape) {
                            return `<div class="font-medium">${escape(item.text)}</div>`;
                        }
                    }
                });
                if (this.selectedProductId) tomselect.setValue(this.selectedProductId);
                tomselect.on('change', (value) => { this.selectedProductId = value; });
            },
            get currentProduct() { return this.selectedProductId ? this.products[this.selectedProductId] : null; },
            get currentStock() { return this.currentProduct ? this.currentProduct.current_stock : 0; },
            get unit() { return this.currentProduct ? this.currentProduct.unit : ''; },
            get finalStock() {
                const current = parseInt(this.currentStock);
                const added = parseInt(this.quantity) || 0;
                return current + added;
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div x-data="stockForm" x-init="init()">
        <div class="py-8">
            <div class="flex items-center mb-6 space-x-4">
                <a href="{{ route('manajergudang.dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700" title="Kembali"><i class="text-gray-600 fas fa-arrow-left dark:text-gray-300"></i></a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Catat Barang Masuk</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambah stok untuk produk yang sudah ada.</p>
                </div>
            </div>

            <form action="{{ route('manajergudang.stock.in.store') }}" method="POST" class="grid grid-cols-1 gap-8 mt-8 lg:grid-cols-3">
                @csrf
                <div class="p-6 space-y-6 bg-white rounded-lg shadow-md lg:col-span-2 dark:bg-slate-800">
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                            <p class="font-bold">Terjadi Kesalahan</p>
                            <ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div>
                        <label for="product_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Produk <span class="text-red-500">*</span></label>
                        <select id="product_id" name="product_id" placeholder="Cari dan pilih produk..." required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-image_url="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}"
                                        data-stock="{{ $product->current_stock }}"
                                        data-unit="{{ $product->unit }}"
                                        {{ old('product_id', request()->get('product_id')) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Supplier <span class="text-red-500">*</span></label>
                        <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            <option value="" disabled selected>-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Masuk <span class="text-red-500">*</span></label>
                            <input type="number" id="quantity" name="quantity" x-model.number="quantity" placeholder="1" min="1" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                        <div>
                            <label for="transaction_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Contoh: No. PO #12345" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky p-6 bg-white rounded-lg shadow-md top-24 dark:bg-slate-800">
                        <div x-show="!selectedProductId" class="py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="mb-3 text-4xl fas fa-box-open"></i>
                            <p>Pilih produk untuk melihat informasi stok.</p>
                        </div>
                        <div x-show="selectedProductId" x-cloak class="space-y-4">
                            <h3 class="pb-4 text-lg font-semibold text-gray-900 border-b dark:text-white dark:border-slate-700">Informasi Stok</h3>
                            <div class="flex items-center space-x-4">
                                <img :src="currentProduct?.image ? '{{ asset('storage') }}/' + currentProduct.image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentProduct?.name || '')" alt="product" class="object-cover w-16 h-16 rounded-md">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white" x-text="currentProduct?.name"></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`SKU: ${currentProduct?.sku}`"></p>
                                </div>
                            </div>
                            <div class="pt-4 space-y-2 border-t dark:border-slate-700">
                                <div class="flex items-center justify-between"><span class="text-sm text-gray-600 dark:text-gray-400">Stok Saat Ini:</span><span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentStock} ${unit}`"></span></div>
                                <div class="flex items-center justify-between text-sm"><span class="text-gray-600 dark:text-gray-400">Jumlah Masuk:</span><span class="font-semibold text-green-500" x-text="`+ ${quantity || 0} ${unit}`"></span></div>
                            </div>
                            <div class="pt-4 border-t dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Stok Setelahnya:</span>
                                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400" x-text="`${finalStock} ${unit}`"></span>
                                </div>
                            </div>
                            <div class="pt-6">
                                <button type="submit" class="flex items-center justify-center w-full px-6 py-3 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700"><i class="mr-2 fas fa-save"></i>Simpan Transaksi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection