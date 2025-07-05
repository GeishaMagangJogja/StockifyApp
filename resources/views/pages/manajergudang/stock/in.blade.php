@extends('layouts.dashboard')

@section('title', 'Ajukan Permintaan Barang Masuk')

@push('scripts')
{{-- Script Alpine.js untuk interaktivitas form --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockInForm', () => ({
            // Ambil data produk dari controller
            products: {!! json_encode($products->keyBy('id')->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'image' => $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=1e293b&color=fff',
                    'current_stock' => (int) $product->current_stock,
                    'unit' => $product->unit,
                ];
            })) !!},
            selectedProductId: '{{ old('product_id', request()->get('product_id')) }}' || null,
            
            // Inisialisasi TomSelect untuk dropdown produk
            init() {
                const selectEl = document.getElementById('product_id');
                if (!selectEl) return;

                const tomselect = new TomSelect(selectEl, {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: 'Cari dan pilih produk...'
                });

                if (this.selectedProductId) {
                    tomselect.setValue(this.selectedProductId);
                }

                tomselect.on('change', (value) => { this.selectedProductId = value; });
            },

            // Helper untuk mendapatkan data produk yang dipilih
            get currentProduct() {
                return this.selectedProductId ? this.products[this.selectedProductId] : null;
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8" x-data="stockInForm" x-init="init()">
    
    <!-- Header Halaman -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous(route('manajergudang.dashboard')) }}" class="flex items-center justify-center w-10 h-10 text-gray-700 bg-gray-100 rounded-full dark:bg-slate-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 md:text-3xl dark:text-white">Ajukan Permintaan Barang Masuk</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Permintaan ini akan menunggu konfirmasi dari Staff Gudang sebelum stok diperbarui.</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('manajergudang.stock.in.store') }}" method="POST" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        @csrf
        <!-- Kolom Kiri: Form Input Utama -->
        <div class="p-6 space-y-6 bg-white border border-gray-200 shadow-sm lg:col-span-2 rounded-xl dark:bg-slate-800 dark:border-slate-700">
            @if ($errors->any())
                <div class="p-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300" role="alert">
                    <span class="font-medium">Terjadi Kesalahan Validasi!</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            
            <div>
                <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produk <span class="text-red-500">*</span></label>
                <select id="product_id" name="product_id" required>
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id', request()->get('product_id')) == $product->id)>
                        {{ $product->name }} (Stok: {{ $product->current_stock }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                <select id="supplier_id" name="supplier_id" class="w-full px-4 py-2 text-sm text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                    <option value="" disabled selected>-- Pilih Supplier --</option>
                    @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Diajukan <span class="text-red-500">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="w-full px-4 py-2 text-sm text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                </div>
                <div>
                    <label for="transaction_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Permintaan <span class="text-red-500">*</span></label>
                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="w-full px-4 py-2 text-sm text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500" required>
                </div>
            </div>
            <div>
                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: No. PO #12345, untuk restock bulanan" class="w-full px-4 py-2 text-sm text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Kolom Kanan: Info & Submit -->
        <div class="lg:col-span-1">
            <div class="sticky p-6 bg-white border border-gray-200 shadow-sm top-24 rounded-xl dark:bg-slate-800 dark:border-slate-700">
                <h3 class="pb-4 text-lg font-semibold text-gray-900 border-b border-gray-200 dark:text-white dark:border-slate-700">Informasi Produk Terpilih</h3>
                <div x-show="!currentProduct" class="py-10 text-center">
                    <x-empty-state icon="fa-box" title="Pilih Produk" message="Pilih produk untuk melihat detail stok saat ini." />
                </div>
                <div x-show="currentProduct" x-cloak class="mt-4 space-y-4">
                    <div class="flex items-center gap-4">
                        <img :src="currentProduct.image" :alt="currentProduct.name" class="object-cover w-16 h-16 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white" x-text="currentProduct.name"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="`SKU: ${currentProduct.sku}`"></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-slate-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stok Saat Ini:</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="`${currentProduct.current_stock} ${currentProduct.unit}`"></span>
                    </div>
                </div>
                 <div class="pt-6 mt-6 border-t border-gray-200 dark:border-slate-700">
                    <button type="submit" class="flex items-center justify-center w-full px-6 py-3 font-semibold text-white transition-all bg-cyan-600 rounded-lg shadow-md hover:bg-cyan-700 focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        <i class="mr-2 fas fa-paper-plane"></i>Ajukan Permintaan
                    </button>
                    <p class="mt-3 text-xs text-center text-gray-500 dark:text-gray-400">Status transaksi akan <span class="font-semibold">Pending</span> setelah diajukan.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection