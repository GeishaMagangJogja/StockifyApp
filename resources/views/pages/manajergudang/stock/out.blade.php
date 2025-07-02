@extends('layouts.dashboard')

@section('title', 'Catat Barang Keluar')

@push('scripts')
{{-- Script Alpine.js --}}
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
                    render: {
                        option: function(data, escape) {
                            return `<div class="flex items-center space-x-3"><img class="w-10 h-10 object-cover rounded" src="${escape(data.image_url)}" alt="${escape(data.text)}"><div><div class="font-medium">${escape(data.text)}</div><div class="text-xs text-gray-500">Stok: ${escape(data.stock)} ${escape(data.unit)}</div></div></div>`;
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
                const removed = parseInt(this.quantity) || 0;
                return current - removed;
            },
            validateQuantity() {
                if (this.quantity > this.currentStock) { alert('Jumlah keluar tidak boleh melebihi stok saat ini!'); this.quantity = this.currentStock; }
                if (this.quantity < 1) { this.quantity = 1; }
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div x-data="stockForm" x-init="init()">
        <div class="py-8">
            {{-- [IMPROVEMENT] Header yang lebih terintegrasi --}}
            <div class="flex items-center gap-4 mb-8">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50">
                    <i class="text-xl text-red-600 fas fa-minus dark:text-red-400"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Catat Barang Keluar</h1>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Kurangi stok untuk produk yang digunakan atau dijual.</p>
                </div>
            </div>
            <form action="{{ route('manajergudang.stock.out.store') }}" method="POST" class="grid grid-cols-1 gap-8 mt-8 lg:grid-cols-3">
                @csrf
                <div class="p-6 space-y-6 bg-white rounded-xl shadow-lg lg:col-span-2 dark:bg-slate-800">
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert"><p class="font-bold">Terjadi Kesalahan</p><ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <div><label for="product_id" class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"><i class="w-5 mr-2 fas fa-box"></i>Produk <span class="ml-1 text-red-500">*</span></label><select id="product_id" name="product_id" placeholder="Cari dan pilih produk..." required>@foreach ($products as $product)<option value="{{ $product->id }}" data-image_url="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" data-stock="{{ $product->current_stock }}" data-unit="{{ $product->unit }}" {{ old('product_id', request()->get('product_id')) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>@endforeach</select></div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div><label for="quantity" class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"><i class="w-5 mr-2 fas fa-cubes"></i>Jumlah Keluar <span class="ml-1 text-red-500">*</span></label><input type="number" id="quantity" name="quantity" x-model.number="quantity" @change="validateQuantity()" placeholder="1" min="1" :max="currentStock" class="w-full px-4 py-2 text-sm border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required></div>
                        <div><label for="transaction_date" class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"><i class="w-5 mr-2 fas fa-calendar-alt"></i>Tanggal Transaksi <span class="ml-1 text-red-500">*</span></label><input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2 text-sm border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required></div>
                    </div>
                    <div><label for="notes" class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"><i class="w-5 mr-2 fas fa-pencil-alt"></i>Tujuan / Catatan (Opsional)</label><textarea id="notes" name="notes" rows="3" placeholder="Contoh: Untuk Proyek Gedung A" class="w-full px-4 py-2 text-sm border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea></div>
                </div>
                <div class="lg:col-span-1">
                    <div class="sticky p-6 bg-white rounded-xl shadow-lg top-24 dark:bg-slate-800">
                        <div x-show="!selectedProductId" class="py-10 text-center text-gray-500 dark:text-gray-400"><i class="mb-4 text-5xl fas fa-box-open opacity-50"></i><p>Pilih produk untuk melihat informasi stok.</p></div>
                        <div x-show="selectedProductId" x-cloak class="space-y-4">
                            <h3 class="flex items-center pb-4 text-lg font-semibold text-gray-900 border-b dark:text-white dark:border-slate-700"><i class="mr-3 text-blue-500 fas fa-info-circle"></i>Informasi Stok</h3>
                            <div class="flex items-center space-x-4"><img :src="currentProduct?.image ? '{{ asset('storage') }}/' + currentProduct.image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentProduct?.name || '')" alt="product" class="object-cover w-16 h-16 rounded-lg"><div><p class="font-semibold text-gray-800 dark:text-white" x-text="currentProduct?.name"></p><p class="text-sm text-gray-500 dark:text-gray-400" x-text="`SKU: ${currentProduct?.sku}`"></p></div></div>
                            <div class="pt-4 space-y-2 border-t dark:border-slate-700">
                                <div class="flex items-center justify-between"><span class="text-sm text-gray-600 dark:text-gray-400">Stok Saat Ini:</span><span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentStock} ${unit}`"></span></div>
                                <div class="flex items-center justify-between text-sm"><span class="text-gray-600 dark:text-gray-400">Jumlah Keluar:</span><span class="font-semibold text-red-500" x-text="`- ${quantity || 0} ${unit}`"></span></div>
                            </div>
                            <div class="pt-4 border-t dark:border-slate-700">
                                <div class="flex items-center justify-between"><span class="font-medium text-gray-600 dark:text-gray-400">Stok Setelahnya:</span><span class="text-2xl font-bold" :class="finalStock < 0 ? 'text-red-500' : 'text-blue-600 dark:text-blue-400'" x-text="`${finalStock} ${unit}`"></span></div>
                            </div>
                            <div class="pt-6"><button type="submit" class="flex items-center justify-center w-full px-6 py-3 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed" :disabled="finalStock < 0 || quantity < 1"><i class="mr-2 fas fa-save"></i>Simpan Transaksi</button><p x-show="finalStock < 0" class="mt-2 text-xs text-center text-red-500">Stok tidak boleh negatif!</p></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection