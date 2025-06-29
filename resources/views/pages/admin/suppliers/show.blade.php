@extends('layouts.dashboard')

@section('title', 'Detail Supplier: ' . $supplier->name)

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.suppliers.index') }}" class="hover:text-blue-600">Supplier</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Supplier: {{ $supplier->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Informasi lengkap tentang supplier ini</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                   class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-150">
                    <i class="mr-1 fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete(this.form)"
                            class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                        <i class="mr-1 fas fa-trash-alt"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="flex items-center text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="mr-2 text-blue-500 fas fa-info-circle"></i>
                        Informasi Supplier
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Supplier</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->contact_person }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->phone }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->email ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</h3>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-line dark:text-white">{{ $supplier->address }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Pada</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $supplier->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="flex items-center text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="mr-2 text-blue-500 fas fa-boxes"></i>
                        Statistik Produk
                    </h2>
                </div>
                <div class="p-6">
                    <div class="py-4 text-center">
                        <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $supplier->products()->count() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk dari Supplier Ini</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.products.index') }}?supplier_id={{ $supplier->id }}"
                           class="block w-full px-4 py-2 text-center text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                            Lihat Daftar Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(form) {
            Swal.fire({
                title: 'Hapus Supplier?',
                text: "Data supplier akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @endpush
@endsection
