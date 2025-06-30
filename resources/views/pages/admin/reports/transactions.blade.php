@extends('layouts.dashboard')

@section('title', 'Laporan Transaksi Barang')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
    
    {{-- Card Header: Title & Filters --}}
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Laporan Transaksi Barang</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail riwayat barang masuk dan keluar.</p>
            </div>
            
            {{-- Filter Form --}}
            <form method="GET" class="mt-4 sm:mt-0 flex flex-wrap items-center gap-4">
                <select name="type" class="w-48 px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Jenis --</option>
                    <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">

                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-md font-medium shadow-sm transition">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.reports.transactions') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">Reset</a>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                    <th scope="col" class="px-6 py-3 text-center font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Pengguna</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $trx->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             @if($trx->type == 'masuk')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    Barang Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    Barang Keluar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-medium {{ $trx->type == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $trx->type == 'masuk' ? '+' : '-' }}{{ $trx->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">{{ $trx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                         <td colspan="5" class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                            <p>Tidak ada data transaksi ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
     @if ($transactions->hasPages())
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection