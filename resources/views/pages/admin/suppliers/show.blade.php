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
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Supplier</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 bg-yellow-600 rounded-lg hover:bg-yellow-700">
                <i class="mr-2 fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition duration-150 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                <i class="mr-2 fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Informasi Supplier -->
    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800 lg:col-span-1">
        <div class="p-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-16 h-16 text-blue-600 bg-blue-100 rounded-full dark:text-blue-300 dark:bg-blue-900">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $supplier->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terdaftar sejak {{ $supplier->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $supplier->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $supplier->phone }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $supplier->address ?? 'Tidak ada alamat' }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 w-5 h-5 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $supplier->products_count }} produk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800 lg:col-span-2">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Produk dari Supplier Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Stok</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">SKU</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($supplier->products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="flex items-center justify-center w-full h-full text-blue-600 bg-blue-100 rounded-full dark:text-blue-300 dark:bg-blue-900">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $product->category->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 {{ $product->current_stock <= 0 ? 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-200' : 'text-green-800 bg-green-100 dark:bg-green-900 dark:text-green-200' }} rounded-full">
                                {{ $product->current_stock }} {{ $product->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            {{ $product->sku }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-4 font-medium text-gray-500">Supplier ini belum memiliki produk</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
