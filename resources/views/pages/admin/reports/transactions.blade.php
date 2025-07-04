@extends('layouts.dashboard')

@section('title', 'Laporan Transaksi Barang')

@section('content')
<!-- Header Section with Glass Effect -->
<div class="relative mb-8 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl backdrop-blur-sm"></div>
    <div class="relative p-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center mb-4 space-x-2 text-sm">
            <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                <i class="mr-2 fas fa-file-alt"></i>Laporan
            </span>
            <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                <i class="mr-2 fas fa-exchange-alt"></i>Transaksi Barang
            </span>
        </nav>

        <!-- Title Section -->
        <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="mr-3 text-blue-600 fas fa-chart-line dark:text-blue-400"></i>
                    Laporan Transaksi Barang
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Detail riwayat barang masuk dan keluar
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="mb-8 overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
    <div class="p-6">
        <form method="GET" class="flex flex-col items-start gap-4 sm:flex-row sm:items-end">
            <div class="flex flex-col w-full space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 sm:w-auto">
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Transaksi</label>
                    <select name="type" id="type" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm sm:w-48 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Jenis --</option>
                        <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div>
                    <label for="from" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" name="from" id="from" value="{{ request('from') }}" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="to" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" value="{{ request('to') }}" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center w-full space-x-3 sm:w-auto">
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                    <i class="mr-2 fas fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.reports.transactions') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg sm:w-auto hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 focus:outline-none dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    <i class="mr-2 fas fa-sync-alt"></i>
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table Section -->
<div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <!-- Enhanced Table Header -->
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3">Produk</th>
                    <th scope="col" class="px-6 py-3">Jenis</th>
                    <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                    <th scope="col" class="px-6 py-3">Pengguna</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Catatan</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $trx)
                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $trx->product->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($trx->type == 'Masuk')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                    <i class="mr-1 fas fa-arrow-down"></i>
                                    Barang Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                                    <i class="mr-1 fas fa-arrow-up"></i>
                                    Barang Keluar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-medium whitespace-nowrap {{ $trx->type == 'Masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $trx->type == 'Masuk' ? '+' : '-' }}{{ $trx->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $trx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $trx->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="mx-auto mb-3 text-4xl fas fa-exchange-alt"></i>
                            <p class="mb-2">Tidak ada data transaksi ditemukan.</p>
                            @if(request()->anyFilled(['type', 'from', 'to']))
                                <a href="{{ route('admin.reports.transactions') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    <i class="mr-1 fas fa-sync-alt"></i> Reset filter
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($transactions->hasPages())
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
