@extends('layouts.dashboard')

@section('title', 'Detail Kategori: ' . $category->name)

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span>/</span>
            <span>Detail Kategori</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Kategori: {{ $category->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Informasi lengkap tentang kategori produk ini</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.edit', $category->id) }}"
                   class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-150">
                    <i class="mr-1 fas fa-edit"></i> Edit
                </a>
                <button onclick="confirmDelete()"
                        class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="mr-1 fas fa-trash-alt"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Informasi Utama Kategori --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="flex items-center text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="mr-2 text-blue-500 fas fa-info-circle"></i>
                        Informasi Kategori
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Kategori</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produk</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $category->products_count ?? $category->products()->count() }} produk
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $category->description ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $category->created_at->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Pada</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $category->updated_at->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Kategori --}}
        <div>
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="flex items-center text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="mr-2 text-blue-500 fas fa-chart-pie"></i>
                        Statistik Produk
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Total Produk</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $category->products_count ?? $category->products()->count() }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Stok Rendah</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @php
                                        $lowStockCount = $category->products()->whereColumn('current_stock', '<=', 'min_stock')
                                            ->where('current_stock', '>', 0)
                                            ->count();
                                    @endphp
                                    {{ $lowStockCount }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-yellow-500 h-2.5 rounded-full"
                                     style="width: {{ $category->products_count ? ($lowStockCount / $category->products_count) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Stok Habis</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @php
                                        $outOfStockCount = $category->products()->where('current_stock', '<=', 0)->count();
                                    @endphp
                                    {{ $outOfStockCount }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-red-600 h-2.5 rounded-full"
                                     style="width: {{ $category->products_count ? ($outOfStockCount / $category->products_count) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- Daftar Produk dalam Kategori --}}
<div class="mt-6 overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="flex items-center text-lg font-semibold text-gray-800 dark:text-white">
            <i class="mr-2 text-blue-500 fas fa-boxes"></i>
            Produk dalam Kategori Ini
        </h2>
        <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}"
           class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-150">
            <i class="mr-1 fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">SKU</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Nama Produk</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Stok</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Status</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($category->products as $product)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if($product->sku)
                                {{ $product->sku }}
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            {{ $product->current_stock }} {{ $product->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->current_stock == 0)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                    Habis
                                </span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                    Tersedia
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                   class="p-1 text-blue-600 rounded-full hover:bg-blue-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="p-1 text-yellow-600 rounded-full hover:bg-yellow-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                            Tidak ada produk dalam kategori ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($category->products()->count() > 10)
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $category->products()->paginate(10)->links() }}
        </div>
    @endif
</div>

    {{-- Delete Form --}}
    <form id="deleteForm" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Semua produk dalam kategori ini akan tetap ada tetapi tidak memiliki kategori!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
    @endpush
@endsection
