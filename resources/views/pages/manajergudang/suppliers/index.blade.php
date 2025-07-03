@extends('layouts.dashboard')

@section('title', 'Daftar Supplier')

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white"><i class="mr-3 text-blue-500 fas fa-truck"></i>Daftar Supplier</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Lihat semua data supplier yang bekerja sama dengan perusahaan.</p>
        </div>

        {{-- [BARU] Kartu Statistik Langsung --}}
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Supplier</h4>
                        <p class="mt-1 text-4xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['total_suppliers']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full dark:bg-blue-900/30"><i class="text-xl text-blue-500 fas fa-truck"></i></div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk dari Supplier</h4>
                        <p class="mt-1 text-4xl font-bold text-green-500">{{ number_format($stats['total_products_from_suppliers']) }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full dark:bg-green-900/30"><i class="text-xl text-green-500 fas fa-boxes"></i></div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Produk</h4>
                        <p class="mt-1 text-4xl font-bold text-yellow-500">{{ $stats['total_suppliers'] > 0 ? round($stats['total_products_from_suppliers'] / $stats['total_suppliers'], 1) : 0 }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full dark:bg-yellow-900/30"><i class="text-xl text-yellow-500 fas fa-chart-bar"></i></div>
                </div>
            </div>
        </div>

        {{-- Panel Filter & Tabel --}}
        <div class="overflow-hidden bg-white rounded-xl shadow-lg dark:bg-slate-800">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <form action="{{ route('manajergudang.suppliers.index') }}" method="GET">
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><i class="text-gray-400 fas fa-search"></i></div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau kontak..." class="w-full py-2.5 pl-12 pr-4 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-center gap-2">
                             <button type="submit" class="flex items-center justify-center w-full px-6 py-2.5 text-white bg-blue-600 rounded-lg md:w-auto hover:bg-blue-700"><i class="mr-2 fas fa-filter"></i>Cari</button>
                            @if(request('search'))
                                <a href="{{ route('manajergudang.suppliers.index') }}" class="p-2.5 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300" title="Reset"><i class="fas fa-undo"></i></a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400"><i class="mr-2 fas fa-truck"></i>Supplier</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400"><i class="mr-2 fas fa-address-card"></i>Contact Person</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400"><i class="mr-2 fas fa-boxes"></i>Total Produk</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400"><i class="mr-2 fas fa-eye"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-slate-700 dark:bg-slate-800">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="object-cover w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($supplier->name) }}&background=1e293b&color=fff" alt="{{ $supplier->name }}">
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $supplier->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $supplier->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $supplier->contact_person ?? '-' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $supplier->phone }}</div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">
                                        {{ $supplier->products_count }} Produk
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-center whitespace-nowrap">
                                     <a href="{{ route('manajergudang.suppliers.show', $supplier) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 transition-colors duration-150 rounded-full hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/30" title="Lihat Detail">
                                         <i class="fas fa-eye"></i>
                                     </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400"><div class="flex flex-col items-center"><i class="mb-4 text-5xl fas fa-truck-loading opacity-50"></i><p>Tidak ada data supplier ditemukan.</p></div></td></tr>
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