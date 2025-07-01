@extends('layouts.dashboard')

@section('title', 'Manajemen Supplier')

@section('content')
<div class="mb-6">
    <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <span>Supplier</span>
    </div>
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Supplier</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola data supplier dan produk yang mereka sediakan
            </p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Supplier
        </a>
    </div>
</div>

<div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
            <form action="{{ route('admin.suppliers.index') }}" method="GET" class="w-full md:w-auto">
                <div class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari supplier..."
                           class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <button type="submit" class="px-4 py-2 ml-2 text-sm font-medium text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Total: {{ $suppliers->total() }} supplier
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        <div class="flex items-center">
                            Nama Supplier
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </a>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Kontak</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10">
                                <div class="flex items-center justify-center w-full h-full text-blue-600 bg-blue-100 rounded-full dark:text-blue-300 dark:bg-blue-900">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $supplier->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $supplier->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $supplier->contact_person }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $supplier->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                            {{ $supplier->products_count }} Produk
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                               class="p-2 text-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 dark:text-blue-400"
                               title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                               class="p-2 text-yellow-600 rounded-lg hover:bg-yellow-50 dark:hover:bg-gray-700 dark:text-yellow-400"
                               title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @if($supplier->products_count == 0)
                                <a href="{{ route('admin.suppliers.delete', $supplier->id) }}"
                                   class="p-2 text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400"
                                   title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </a>
                            @else
                                <button disabled
                                        class="p-2 text-gray-400 cursor-not-allowed dark:text-gray-500"
                                        title="Tidak dapat dihapus karena memiliki produk">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center py-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-4 font-medium text-gray-500">Tidak ada data supplier</p>
                            <p class="text-sm text-gray-400">Tambahkan supplier baru dengan menekan tombol di atas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $suppliers->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
