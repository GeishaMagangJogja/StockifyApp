@extends('layouts.dashboard')

@section('title', 'Konfirmasi Hapus Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Produk</a>
            <span>/</span>
            <span>Konfirmasi Hapus</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus Produk</h1>
                <p class="text-gray-600 dark:text-gray-400">Anda akan menghapus produk ini secara permanen</p>
            </div>
        </div>
    </div>

    <!-- Display any error messages -->
    @if(session('error'))
        <div class="p-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded-lg dark:bg-red-900/20 dark:text-red-300 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="text-red-500 fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Error</h3>
                    <div class="mt-2 text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-24 h-24 mr-6">
                    <img class="object-cover w-full h-full rounded-lg"
                         src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}"
                         alt="{{ $product->name }}"
                         onerror="this.src='{{ asset('images/default-product.png') }}'">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <p><span class="font-medium">SKU:</span> {{ $product->sku }}</p>
                        <p><span class="font-medium">Kategori:</span> {{ $product->category->name ?? '-' }}</p>
                        <p><span class="font-medium">Supplier:</span> {{ $product->supplier->name ?? '-' }}</p>
                        <p><span class="font-medium">Stok:</span> {{ $product->current_stock ?? 0 }} {{ $product->unit }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="p-4 mb-6 text-yellow-800 bg-yellow-100 border-l-4 border-yellow-500 dark:bg-yellow-900/20 dark:text-yellow-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="text-yellow-500 fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">Peringatan!</h3>
                        <div class="mt-2 text-sm">
                            <ul class="pl-5 space-y-1 list-disc">
                                <li>Produk ini memiliki {{ $product->stockTransactionsCount ?? $product->stockTransactions()->count() }} riwayat transaksi stok</li>
                                <li>Semua data terkait produk ini akan dihapus secara permanen</li>
                                <li>Tindakan ini tidak dapat dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regular Delete Form -->
            <form id="deleteForm" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="mb-4">
                @csrf
                @method('DELETE')

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700">
                        <i class="mr-1 fas fa-trash-alt"></i> Hapus Permanen
                    </button>
                </div>
            </form>

            <!-- Force Delete Form (for troubleshooting) -->
            <form id="forceDeleteForm" action="{{ route('admin.products.force-destroy', $product->id) }}" method="POST" class="pt-4 border-t border-gray-200 dark:border-gray-700">
                @csrf
                @method('DELETE')

                <div class="p-3 mb-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900/20 dark:text-red-300">
                    <strong>Jika penghapusan normal gagal:</strong> Gunakan tombol "Force Delete" di bawah ini untuk menghapus secara paksa.
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus secara paksa? Ini akan mengabaikan semua constraint database.')"
                            class="px-4 py-2 text-white bg-red-800 rounded-lg hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-700">
                        <i class="mr-1 fas fa-exclamation-triangle"></i> Force Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add loading state to delete buttons
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="mr-1 fas fa-spinner fa-spin"></i> Menghapus...';
        });

        document.getElementById('forceDeleteForm').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="mr-1 fas fa-spinner fa-spin"></i> Force Deleting...';
        });
    </script>
@endsection
