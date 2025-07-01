@extends('layouts.dashboard')

@section('title', 'Detail Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Produk</a>
            <span>/</span>
            <span>Detail Produk</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Produk: {{ $product->name }}</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Informasi Produk -->
        <div class="col-span-2">
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Produk</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Produk</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->name }}</p>
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">SKU</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->sku }}</p>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $product->category ? $product->category->name : '-' }}
                            </p>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $product->supplier ? $product->supplier->name : '-' }}
                            </p>
                        </div>

                        <!-- Harga Beli -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Harga Jual -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Stok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Stok</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{-- [FIX] Gunakan accessor 'current_stock' yang dihitung dari transaksi --}}
                                {{ $product->current_stock }}
                                {{-- [FIX] Gunakan 'current_stock' juga untuk perbandingan --}}
                                @if($product->current_stock <= $product->min_stock)
                                    <span class="ml-2 text-xs text-red-500">(Stok Rendah)</span>
                                @endif
                            </p>
                        </div>

                        <!-- Minimum Stok -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Minimum Stok</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->min_stock }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </p>
                        </div>

                        <!-- Dibuat Pada -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $product->created_at->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>

                        <!-- Diupdate Pada -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Diupdate Pada</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $product->updated_at->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $product->description ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gambar Produk dan Aksi -->
        <div>
            <!-- Gambar Produk -->
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Gambar Produk</h2>
                </div>
                <div class="p-6">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover w-full rounded">
                    @else
                        <div class="flex items-center justify-center p-6 bg-gray-100 rounded dark:bg-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tidak ada gambar</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Aksi</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="mr-2 fas fa-edit"></i> Edit Produk
                        </a>

                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="mr-2 fas fa-trash"></i> Hapus Produk
                            </button>
                        </form>

                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                            <i class="mr-2 fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection