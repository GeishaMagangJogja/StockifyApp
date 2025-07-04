@extends('layouts.dashboard')

@section('title', 'Laporan Arus Barang')

@push('styles')
<style>
    .sortable-header { cursor: pointer; transition: background-color 0.2s ease-in-out; }
    .sortable-header:hover { background-color: rgba(0, 0, 0, 0.05); }
    .dark .sortable-header:hover { background-color: rgba(255, 255, 255, 0.05); }
    .sort-icon { transition: transform 0.2s ease-in-out, color 0.2s; }
    a:hover .sort-icon { color: #06b6d4; } /* Warna Cyan-500 saat hover */
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <!-- Header Halaman - Gaya Manajer -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Audit Arus Barang</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Jejak terperinci untuk setiap pergerakan masuk dan keluar inventori.</p>
            </div>
             <a href="{{ route('manajergudang.reports.transactions') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600">
                <i class="mr-2 fas fa-sync-alt"></i>Reset
            </a>
        </div>
        <div class="mt-4 border-b border-gray-200 dark:border-slate-700"></div>
    </div>

    <!-- Kartu Statistik - Gaya Manajer -->
    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card icon="fa-exchange-alt" color="cyan" title="Total Transaksi" value="{{ number_format($stats['total']) }}" />
        <x-stat-card icon="fa-arrow-down" color="green" title="Transaksi Masuk" value="{{ number_format($stats['incoming']) }}" />
        <x-stat-card icon="fa-arrow-up" color="red" title="Transaksi Keluar" value="{{ number_format($stats['outgoing']) }}" />
        <x-stat-card icon="fa-cubes" color="purple" title="Total Unit Bergerak" value="{{ number_format($stats['total_quantity']) }}" />
    </div>

    <!-- Panel Kontrol -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-slate-800 dark:border-slate-700">
        <!-- Form Filter -->
        <div class="p-6">
            <form action="{{ route('manajergudang.reports.transactions') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                     <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk, user, supplier..."
                           class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg md:col-span-2 dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                    <select name="type" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Semua Tipe</option>
                        <option value="Masuk" @selected(request('type') == 'Masuk')>Masuk</option>
                        <option value="Keluar" @selected(request('type') == 'Keluar')>Keluar</option>
                    </select>
                    <button type="submit" class="flex items-center justify-center w-full px-5 py-2 font-semibold text-white transition-all bg-cyan-600 rounded-lg hover:bg-cyan-700 focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        <i class="mr-2 fas fa-search"></i>Cari
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="text-xs text-gray-500">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                    <div>
                        <label for="end_date" class="text-xs text-gray-500">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 text-gray-900 bg-gray-50 border-gray-300 rounded-lg dark:text-white dark:bg-slate-700 dark:border-slate-600 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto border-t border-gray-200 dark:border-slate-700">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                 <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-700/50 dark:text-gray-400">
                    <tr>
                         @php
                            function getSortLink($column, $sortBy, $sortDirection) {
                                $direction = ($sortBy == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $column, 'direction' => $direction]);
                            }
                        @endphp
                        <th scope="col" class="px-6 py-3 w-1/4 sortable-header">
                            <a href="{{ getSortLink('date', $sortBy, $sortDirection) }}" class="flex items-center">
                                TANGGAL
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'date') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/3 sortable-header">
                            <a href="{{ getSortLink('product_name', $sortBy, $sortDirection) }}" class="flex items-center">
                                DETAIL PRODUK & TRANSAKSI
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'product_name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                         <th scope="col" class="px-6 py-3 w-1/4 sortable-header">
                            <a href="{{ getSortLink('user_name', $sortBy, $sortDirection) }}" class="flex items-center">
                                STAFF BERTUGAS
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'user_name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right sortable-header">
                            <a href="{{ getSortLink('quantity', $sortBy, $sortDirection) }}" class="inline-flex items-center">
                                JUMLAH
                                <i class="ml-1.5 text-gray-400 fas sort-icon @if($sortBy == 'quantity') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $transaction->date->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->date->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $transaction->product->name ?? 'Produk Dihapus' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                     @if($transaction->type == 'Masuk' && $transaction->supplier)
                                        <i class="fas fa-truck fa-fw mr-1"></i> Dari: {{ $transaction->supplier->name }}
                                    @elseif($transaction->notes)
                                        <i class="fas fa-comment-alt fa-fw mr-1"></i> {{ Str::limit($transaction->notes, 40) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                             <td class="px-6 py-4 text-gray-900 dark:text-white">
                                <div class="font-medium">{{ $transaction->user->name ?? 'Sistem' }}</div>
                                <div class="text-xs text-gray-500">{{ $transaction->user->role ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                 <div class="text-lg font-bold {{ $transaction->type == 'Masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $transaction->type == 'Masuk' ? '+' : '-' }} {{ number_format($transaction->quantity) }}
                                    <span class="text-xs font-normal text-gray-500">{{ $transaction->product->unit ?? 'unit' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                 <x-empty-state title="Data Transaksi Tidak Ditemukan"
                                    message="Silakan sesuaikan filter pencarian Anda."
                                    icon="fa-search" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-slate-700">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection