@extends('layouts.dashboard')

@section('title', 'Detail Kategori: ' . $category->name)

@section('content')
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span>/</span>
            <span>Detail Kategori</span>
        </div>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Kategori: {{ $category->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Informasi lengkap tentang kategori produk ini</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.edit', $category->id) }}"
                   class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-150">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <button onclick="confirmDelete()"
                        class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Informasi Utama Kategori --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Kategori
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                {{ $category->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Pada</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $category->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Kategori --}}
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                        Statistik
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Total Produk</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $category->products()->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Stok Rendah</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $category->products()->whereColumn('stock', '<=', 'min_stock')->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-yellow-500 h-2.5 rounded-full"
                                     style="width: {{ $category->products()->count() > 0 ? ($category->products()->whereColumn('stock', '<=', 'min_stock')->count() / $category->products()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Stok Habis</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $category->products()->where('stock', 0)->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-red-600 h-2.5 rounded-full"
                                     style="width: {{ $category->products()->count() > 0 ? ($category->products()->where('stock', 0)->count() / $category->products()->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Produk dalam Kategori --}}
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                <i class="fas fa-boxes text-blue-500 mr-2"></i>
                Produk dalam Kategori Ini
            </h2>
            <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}"
               class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-150">
                <i class="fas fa-plus mr-1"></i> Tambah Produk
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($category->products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $product->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->stock ?? 0 }} {{ $product->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(($product->stock ?? 0) == 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Habis
                                    </span>
                                @elseif(($product->stock ?? 0) <= ($product->min_stock ?? 0))
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Stok Rendah
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
