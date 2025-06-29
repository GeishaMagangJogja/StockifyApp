@extends('layouts.dashboard')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <span>Produk</span>
        </div>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Produk</h1>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-plus"></i>Tambah Produk
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-800 dark:border-green-600 dark:text-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-800 dark:border-red-600 dark:text-red-200">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="flex flex-wrap items-center gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari produk berdasarkan nama atau SKU..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg min-w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                    </select>

                    <button type="submit" class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>

                    @if(request('search') || request('category') || request('status'))
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                            <i class="fas fa-times mr-1"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Supplier</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Harga</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Stok</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Status</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                               @if($product->image)
                                    <img class="object-cover w-10 h-10 mr-4 rounded-lg"
                                        src="{{ asset('storage/'.$product->image) }}"
                                        alt="{{ $product->name }}"
                                        onerror="this.src='{{ asset('images/no-image.png') }}'">
                                @else
                                    <div class="flex items-center justify-center w-10 h-10 mr-4 bg-gray-300 rounded-lg dark:bg-gray-600">
                                        <i class="text-gray-500 fas fa-box dark:text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            @if($product->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            @if($product->supplier)
                                <div class="truncate max-w-32" title="{{ $product->supplier->name }}">
                                    {{ $product->supplier->name }}
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            <div class="space-y-1">
                                <div class="text-xs text-gray-400">Beli: <span class="font-medium">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span></div>
                                <div class="text-xs text-gray-600 dark:text-gray-300">Jual: <span class="font-medium">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            <div class="flex flex-col">
                                <span class="font-semibold @if($product->stock <= $product->min_stock) text-red-600 dark:text-red-400 @else text-gray-900 dark:text-white @endif">
                                    {{ number_format($product->stock) }}
                                </span>
                                <div class="text-xs text-gray-400">Min: {{ number_format($product->min_stock) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$product->is_active)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                    Tidak Aktif
                                </span>
                            @elseif($product->stock == 0)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100">
                                    Stok Habis
                                </span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-800 dark:text-yellow-100">
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-100">
                                    Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- View Details -->
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-200 dark:hover:bg-blue-700 transition-colors duration-150"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded hover:bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200 dark:hover:bg-yellow-700 transition-colors duration-150"
                                   title="Edit Produk">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete -->
                                <button type="button"
                                        onclick="confirmDelete('{{ $product->id }}', '{{ addslashes($product->name) }}')"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 dark:bg-red-800 dark:text-red-200 dark:hover:bg-red-700 transition-colors duration-150"
                                        title="Hapus Produk">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <i class="text-2xl text-gray-400 fas fa-box-open dark:text-gray-500"></i>
                                </div>
                                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Tidak ada produk</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    @if(request('search') || request('category') || request('status'))
                                        Tidak ada produk yang sesuai dengan kriteria pencarian.
                                    @else
                                        Belum ada produk yang ditambahkan ke sistem.
                                    @endif
                                </p>
                                @if(request('search') || request('category') || request('status'))
                                    <a href="{{ route('admin.products.index') }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-200 dark:hover:bg-blue-700">
                                        <i class="fas fa-times mr-2"></i>Reset Filter
                                    </a>
                                @else
                                    <a href="{{ route('admin.products.create') }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>Tambah Produk Pertama
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </div>
                <div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    Swal.fire({
        title: 'Hapus Produk?',
        html: `Apakah Anda yakin ingin menghapus produk <strong>"${productName}"</strong>?<br><br><small class="text-gray-500">Data produk dan semua informasi terkait akan dihapus secara permanen dan tidak dapat dikembalikan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'swal-popup',
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus produk, mohon tunggu.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form via AJAX
            axios.delete(`/admin/products/${productId}`)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus produk.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[class*="bg-green-100"], [class*="bg-red-100"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Add some custom styling for SweetAlert
const style = document.createElement('style');
style.textContent = `
    .swal-popup {
        font-family: inherit !important;
    }
    .swal-confirm-btn {
        background-color: #dc2626 !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 500 !important;
    }
    .swal-cancel-btn {
        background-color: #6b7280 !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 500 !important;
        margin-right: 0.5rem !important;
    }
`;
document.head.appendChild(style);
</script>
@endpush
