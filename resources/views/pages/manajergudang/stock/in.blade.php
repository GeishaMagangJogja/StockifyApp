@extends('layouts.dashboard')

@section('title', 'Catat Barang Masuk')

@push('scripts')
{{-- Script untuk interaktivitas form dengan Alpine.js --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockForm', () => ({
            // State (variabel)
            selectedProductId: '{{ old('product_id') }}' || null,
            quantity: '{{ old('quantity') }}' || 0,
            products: {!! json_encode($products->keyBy('id')) !!},
            
            // Properti turunan (computed properties) yang reaktif
            get currentStock() {
                if (this.selectedProductId && this.products[this.selectedProductId]) {
                    return this.products[this.selectedProductId].current_stock ?? 0;
                }
                return '...';
            },
            get unit() {
                if (this.selectedProductId && this.products[this.selectedProductId]) {
                    return this.products[this.selectedProductId].unit;
                }
                return '';
            },
            get finalStock() {
                const current = parseInt(this.currentStock);
                const added = parseInt(this.quantity);
                if (!isNaN(current) && !isNaN(added)) {
                    return current + added;
                }
                return '...';
            },

            // Fungsi untuk inisialisasi
            init() {
                // Tidak perlu lagi, karena sudah di-handle di deklarasi state di atas
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    {{-- Inisialisasi komponen Alpine di elemen pembungkus --}}
    <div x-data="stockForm">
        <div class="py-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Catat Barang Masuk</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Gunakan form ini untuk mencatat penerimaan barang dari supplier.</p>
            </div>

            <form action="{{ route('manajergudang.stock.in.store') }}" method="POST" class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                                {{-- `x-model` akan mengikat nilai select ini ke `selectedProductId` --}}
                                <select id="product_id" name="product_id" x-model="selectedProductId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="" disabled>-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier <span class="text-red-500">*</span></label>
                                <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="" disabled selected>-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Masuk <span class="text-red-500">*</span></label>
                                    {{-- `x-model.number` akan mengikat nilai input ini ke `quantity` --}}
                                    <input type="number" id="quantity" name="quantity" x-model.number="quantity" placeholder="0" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                                <div>
                                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan (Opsional)</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: No. PO #12345" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
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
                                {{-- `x-text` sekarang memanggil computed property `currentStock` dan `unit` --}}
                                <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentStock} ${unit}`">Pilih produk...</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Jumlah Masuk:</span>
                                {{-- `x-text` sekarang memanggil state `quantity` --}}
                                <span class="font-semibold text-green-600" x-text="`+ ${quantity || 0} ${unit}`">+ 0</span>
                            </div>
                            <div class="border-t dark:border-slate-700 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Stok Setelahnya:</span>
                                    {{-- `x-text` sekarang memanggil computed property `finalStock` --}}
                                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400" x-text="`${finalStock} ${unit}`">...</span>
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