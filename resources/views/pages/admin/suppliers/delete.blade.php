@extends('layouts.dashboard')

@section('title', 'Konfirmasi Hapus Supplier')

@section('content')
<div class="max-w-2xl p-6 mx-auto">
    <!-- Breadcrumb -->
    <div class="flex mb-6 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.suppliers.index') }}" class="hover:text-blue-600">Supplier</a>
        <span class="mx-2">/</span>
        <span>Hapus Supplier</span>
    </div>

    <!-- Delete Confirmation Card -->
    <div class="overflow-hidden bg-white border border-red-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-red-800">
        <!-- Card Header -->
        <div class="flex items-center px-6 py-4 border-b border-red-100 bg-red-50 dark:bg-red-900/20 dark:border-red-800">
            <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h2 class="text-lg font-semibold text-red-800 dark:text-red-200">Konfirmasi Penghapusan Supplier</h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Anda akan menghapus supplier berikut:</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-semibold">{{ $supplier->name }}</span> -
                            {{ $supplier->contact_person }} ({{ $supplier->phone }})
                        </p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Produk terkait:</span>
                            {{ $supplier->products_count }} produk
                        </p>

                        @if($supplier->products_count > 0)
                        <div class="p-4 mt-4 border border-yellow-200 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-800">
                            <div class="flex">
                                <svg class="w-5 h-5 mr-2 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span class="text-sm text-yellow-700 dark:text-yellow-300">
                                    Supplier ini memiliki produk terkait. Semua produk akan diupdate dengan supplier_id = NULL.
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="flex justify-between px-6 py-4 border-t border-gray-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
            <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-500">
                Batalkan
            </a>
            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Hapus Supplier
                </button>
            </form>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="p-4 mt-6 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
        <h3 class="flex items-center mb-2 text-sm font-medium text-blue-800 dark:text-blue-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Informasi Penghapusan
        </h3>
        <ul class="space-y-1 text-xs text-blue-700 dark:text-blue-300">
            <li>• Data supplier akan dihapus dari sistem</li>
            <li>• Semua produk yang terkait akan diupdate dengan supplier_id = NULL</li>
            <li>• Aksi ini tidak dapat dibatalkan</li>
        </ul>
    </div>
</div>
@endsection
