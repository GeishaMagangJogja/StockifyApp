@extends('layouts.dashboard')

@section('title', 'Catat Barang Keluar')

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockForm', () => ({
            selectedProductId: '{{ old('product_id') }}' || null,
            quantity: '{{ old('quantity') }}' || '',
            products: {!! json_encode($products->keyBy('id')) !!},
            init() {
                const selectEl = document.getElementById('product_id');
                const tomselect = new TomSelect(selectEl, {
                    create: false,
                    sortField: { field: "text", direction: "asc" }
                });
                if (this.selectedProductId) tomselect.setValue(this.selectedProductId);
                tomselect.on('change', (value) => { this.selectedProductId = value; });
            },
            get currentProduct() { return this.selectedProductId ? this.products[this.selectedProductId] : null; },
            get currentStock() { return this.currentProduct ? this.currentProduct.current_stock : '...'; },
            get unit() { return this.currentProduct ? this.currentProduct.unit : ''; },
            get finalStock() {
                const current = parseInt(this.currentStock);
                const removed = parseInt(this.quantity);
                if (!isNaN(current) && !isNaN(removed)) return current - removed;
                return this.currentStock;
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div x-data="stockForm">
        <div class="py-8">
            <div class="flex items-center space-x-4 mb-6">
                <a href="{{ route('manajergudang.dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 rounded-full shadow hover:bg-gray-100 dark:hover:bg-slate-700 transition" title="Kembali">
                    <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Catat Barang Keluar</h1>
            </div>

            <form action="{{ route('manajergudang.stock.out.store') }}" method="POST" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg shadow p-6 space-y-6">
                    @if ($errors->any())<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert"><p class="font-bold">Terjadi Kesalahan</p><ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul></div>@endif
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Produk <span class="text-red-500">*</span></label>
                        <select id="product_id" name="product_id" placeholder="Cari dan pilih produk..." required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->current_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Keluar <span class="text-red-500">*</span></label>
                            <input type="number" id="quantity" name="quantity" x-model.number="quantity" placeholder="0" min="1" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                        <div>
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tujuan / Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Untuk Proyek Gedung A" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 sticky top-24">
                        <div x-show="!selectedProductId" class="text-center py-10 text-gray-500 dark:text-gray-400"><i class="fas fa-box text-4xl mb-3"></i><p>Pilih produk untuk melihat informasi stok.</p></div>
                        <div x-show="selectedProductId" x-cloak class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">Informasi Stok</h3>
                            <div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-600 dark:text-gray-400">Stok Saat Ini:</span><span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentStock} ${unit}`"></span></div>
                            <div class="flex justify-between items-center text-sm"><span class="text-gray-600 dark:text-gray-400">Jumlah Keluar:</span><span class="font-semibold text-red-500" x-text="`- ${quantity || 0} ${unit}`"></span></div>
                            <div class="border-t dark:border-slate-700 pt-4"><div class="flex justify-between items-center"><span class="text-sm font-medium text-gray-600 dark:text-gray-400">Stok Setelahnya:</span><span class="text-xl font-bold" :class="finalStock < 0 ? 'text-red-500' : 'text-blue-600 dark:text-blue-400'" x-text="`${finalStock} ${unit}`"></span></div></div>
                            <div class="mt-8"><button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-md flex items-center justify-center"><i class="fas fa-save mr-2"></i>Simpan Transaksi</button></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection