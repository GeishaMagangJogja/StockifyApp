@extends('layouts.dashboard')

@section('title', 'Catat Barang Keluar')

@push('scripts')
{{-- Script yang sama dengan barang masuk --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockForm', () => ({
            selectedProductId: null,
            products: {!! json_encode($products->keyBy('id')) !!},
            currentStock: 'Pilih produk...',
            unit: '',

            updateProductInfo() {
                if (this.selectedProductId && this.products[this.selectedProductId]) {
                    this.currentStock = this.products[this.selectedProductId].current_stock ?? 0;
                    this.unit = this.products[this.selectedProductId].unit;
                } else {
                    this.currentStock = 'Pilih produk...';
                    this.unit = '';
                }
            },
            
            init() {
                const oldProductId = '{{ old('product_id') }}';
                if (oldProductId) {
                    this.selectedProductId = oldProductId;
                    this.updateProductInfo();
                }
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div x-data="stockForm">
        <div class="py-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Catat Barang Keluar</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Gunakan form ini untuk mencatat barang yang keluar dari gudang.</p>
            </div>

            <form action="{{ route('manajergudang.stock.out.store') }}" method="POST" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8"> 
                @csrf
                {{-- Kolom Utama (Form) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                        <div class="p-6 space-y-6">
                            @if ($errors->any())
                                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                    <p class="font-bold">Terjadi Kesalahan</p>
                                    <ul>@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif
                            
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Produk <span class="text-red-500">*</span></label>
                                <select id="product_id" name="product_id" x-model="selectedProductId" @change="updateProductInfo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="" disabled>-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Keluar <span class="text-red-500">*</span></label>
                                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" placeholder="Contoh: 50" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                                <div>
                                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tujuan / Catatan (Opsional)</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Untuk Proyek Pembangunan Gedung A" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Samping (Info Stok) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">Informasi Stok</h3>
                        <div class="mt-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Stok Saat Ini:</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentStock} ${unit}`">Pilih produk...</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Jumlah Keluar:</span>
                                <span class="font-semibold text-red-600" x-text="`- ${document.getElementById('quantity').value || 0} ${unit}`">- 0</span>
                            </div>
                            <div class="border-t dark:border-slate-700 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Stok Setelahnya:</span>
                                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400" x-text="`${(parseInt(currentStock) || 0) - (parseInt(document.getElementById('quantity').value) || 0)} ${unit}`">...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                             <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection