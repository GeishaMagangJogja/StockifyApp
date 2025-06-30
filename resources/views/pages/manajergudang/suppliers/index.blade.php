@extends('layouts.dashboard')

@section('title', 'Daftar Supplier')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Supplier</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Lihat semua data supplier yang bekerja sama dengan perusahaan.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
            {{-- Form Pencarian --}}
            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                <form action="{{ route('manajergudang.suppliers.index') }}" method="GET">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email supplier..." class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('manajergudang.suppliers.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                {{-- Kolom Supplier dengan Avatar --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=1e293b&color=fff" alt="{{ $supplier->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($supplier->address, 35) }}</div>
                                        </div>
                                    </div>
                                </td>
                                {{-- Kolom Kontak --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $supplier->email }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $supplier->phone }}</div>
                                </td>
                                {{-- Kolom Total Produk --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{-- Eager load 'products' di controller untuk performa --}}
                                    {{ $supplier->products_count }} Produk
                                </td>
                                {{-- Kolom Aksi (Read-Only) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                     <a href="{{ route('manajergudang.suppliers.show', $supplier) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
                                         <i class="fas fa-eye"></i>
                                     </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada data supplier ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suppliers->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-slate-700">{{ $suppliers->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection