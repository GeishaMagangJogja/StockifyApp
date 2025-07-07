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
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports.export', ['report_type' => 'incoming_goods', 'format' => 'excel']) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-700 dark:hover:bg-green-800">
                    <i class="mr-2 fas fa-file-excel"></i> Export Masuk
                </a>
                <a href="{{ route('admin.reports.export', ['report_type' => 'outgoing_goods', 'format' => 'excel']) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-700 dark:hover:bg-red-800">
                    <i class="mr-2 fas fa-file-excel"></i> Export Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
    <!-- Total Incoming -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang Masuk</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalIncoming) }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg dark:bg-green-900/50">
                    <i class="text-xl text-green-600 fas fa-box-open dark:text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Outgoing -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang Keluar</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalOutgoing) }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg dark:bg-red-900/50">
                    <i class="text-xl text-red-600 fas fa-truck-loading dark:text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Change -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Perubahan</p>
                    <p class="text-2xl font-bold {{ ($totalIncoming - $totalOutgoing) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ ($totalIncoming - $totalOutgoing) >= 0 ? '+' : '' }}{{ number_format($totalIncoming - $totalOutgoing) }}
                    </p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg dark:bg-blue-900/50">
                    <i class="text-xl text-blue-600 fas fa-balance-scale dark:text-blue-400"></i>
                </div>
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

                <!-- Product Filter -->
                <div>
                    <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Produk</label>
                    <select name="product_id" id="product_id" class="w-full px-4 py-2 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm sm:w-48 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Enhanced Table Header -->
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        Produk
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        Jenis
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-300">
                        Jumlah
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        Pengguna
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                        Catatan/Tujuan
                    </th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                @forelse($transactions as $trx)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    @if($trx->product && $trx->product->image)
                                        <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $trx->product->image) }}" alt="{{ $trx->product->name }}">
                                    @else
                                        <div class="flex items-center justify-center w-10 h-10 text-gray-500 bg-gray-200 rounded-full dark:bg-gray-600">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $trx->product->name ?? '-' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $trx->product->sku ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                            @if(strtolower($trx->type) == 'masuk')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <i class="mr-1 fas fa-arrow-down"></i>
                                    Masuk
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <i class="mr-1 fas fa-arrow-up"></i>
                                    Keluar
                                </span>
                            @endif
                        </td>
                      <td class="px-6 py-4 text-center whitespace-nowrap">
                        <span class="{{ strtolower($trx->type) == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                            {{ strtolower($trx->type) == 'masuk' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                        </span>
                    </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $trx->user->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trx->user->role ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('d M Y') : '-' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $trx->date ? \Carbon\Carbon::parse($trx->date)->format('H:i') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate" title="{{ $trx->notes ?? '-' }}">
                                {{ $trx->notes ?? '-' }}
                            </div>
                            @if($trx->type == 'Keluar' && $trx->destination)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Tujuan: {{ $trx->destination }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center justify-center py-10">
                                <i class="mb-4 text-4xl text-gray-400 fas fa-exchange-alt"></i>
                                <p class="mb-2 text-lg font-medium text-gray-600 dark:text-gray-300">Tidak ada data transaksi</p>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ditemukan transaksi yang sesuai dengan filter Anda</p>
                                @if(request()->anyFilled(['type', 'from', 'to', 'product_id']))
                                    <a href="{{ route('admin.reports.transactions') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="mr-2 fas fa-sync-alt"></i> Reset filter
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    Export Data Transaksi
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Pilih jenis transaksi dan format file yang ingin di-export.
                    </p>
                </div>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                        <select id="exportType" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="incoming">Barang Masuk</option>
                            <option value="outgoing">Barang Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Format File</label>
                        <select id="exportFormat" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF (.pdf)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmExport" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Export
                </button>
                <button type="button" id="cancelExport" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Export modal functionality
        const exportModal = document.getElementById('exportModal');
        const openExportButtons = document.querySelectorAll('[data-modal-toggle="exportModal"]');
        const cancelExport = document.getElementById('cancelExport');

        openExportButtons.forEach(button => {
            button.addEventListener('click', function() {
                exportModal.classList.remove('hidden');
            });
        });

        cancelExport.addEventListener('click', function() {
            exportModal.classList.add('hidden');
        });

        document.getElementById('confirmExport').addEventListener('click', function() {
            const type = document.getElementById('exportType').value;
            const format = document.getElementById('exportFormat').value;
            window.location.href = `/admin/reports/export?report_type=${type === 'incoming' ? 'incoming_goods' : 'outgoing_goods'}&format=${format}`;
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
