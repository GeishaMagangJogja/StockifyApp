@extends('layouts.dashboard')

@section('title', $reportTitle)

@section('content')
<!-- Enhanced Header Section -->
<div class="mb-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-sm">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center text-gray-500 transition-colors hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                    <i class="w-4 h-4 mr-2 fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="w-4 h-4 text-gray-400 fas fa-chevron-right"></i>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Laporan Barang Masuk</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with Stats -->
    <div class="relative overflow-hidden shadow-lg p-9 bg-gradient-to-r from-green-500 via-blue-500 to-indigo-500 rounded-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-16 h-16 mr-4 bg-white shadow bg-opacity-30 rounded-xl backdrop-blur-sm">
                        <i class="text-3xl text-white fas fa-box-open"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-white">{{ $reportTitle }}</h1>
                        <p class="mt-1 text-green-100">Riwayat lengkap pemasukan barang ke inventori</p>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="refreshData()"
                            class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white rounded-lg shadow bg-opacity-20 backdrop-blur-sm hover:bg-opacity-40">
                        <i class="mr-2 fas fa-sync-alt" id="refresh-icon"></i>
                        Refresh
                    </button>
                    <div class="relative">
                        <button onclick="toggleExportMenu()"
                                class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white rounded-lg shadow bg-opacity-20 backdrop-blur-sm hover:bg-opacity-40">
                            <i class="mr-2 fas fa-download"></i>
                            Export
                            <i class="ml-2 fas fa-chevron-down"></i>
                        </button>
                        <!-- Export Dropdown -->
                        <div id="export-menu" class="absolute right-0 z-10 hidden w-48 mt-2 bg-white border border-gray-100 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                            <div class="py-2">
                                <a href="#" onclick="exportToExcel(event)" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="mr-3 text-green-500 fas fa-file-excel"></i>
                                    Export ke Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute w-24 h-24 bg-white rounded-full -top-4 -right-4 opacity-10"></div>
        <div class="absolute w-32 h-32 bg-white rounded-full -bottom-6 -left-6 opacity-5"></div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900">
                <i class="text-xl text-green-600 fas fa-box-open dark:text-green-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->total() }}</p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900">
                <i class="text-xl text-blue-600 fas fa-check-circle dark:text-blue-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Diterima</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('status', 'completed')->count() + $transactions->where('status', 'diterima')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-50 dark:bg-yellow-900">
                <i class="text-xl text-yellow-600 fas fa-clock dark:text-yellow-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('status', '!=', 'completed')->where('status', '!=', 'diterima')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-50 dark:bg-purple-900">
                <i class="text-xl text-purple-600 fas fa-calendar-day dark:text-purple-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('date', today()->format('Y-m-d'))->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="p-6 mb-6 bg-white border border-gray-100 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter &amp; Pencarian</h3>
        <button onclick="resetFilters()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
            <i class="mr-1 fas fa-undo"></i>Reset Filter
        </button>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
            <div class="relative">
                <i class="absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2 fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Cari produk, supplier..."
                       class="w-full py-2 pl-10 pr-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="status-filter" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="completed">Completed</option>
                <option value="diterima">Diterima</option>
                <option value="pending">Pending</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
            <input type="date" id="date-start"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
            <input type="date" id="date-end"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
    </div>
</div>

<!-- Enhanced Table -->
<div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="text-gray-500 fas fa-table"></i>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Transaksi</h3>
                <span class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:bg-blue-800 dark:text-blue-200">
                    {{ $transactions->count() }} dari {{ $transactions->total() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto" id="table-container">
        <table class="w-full" id="data-table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-6 py-4 transition-colors cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" onclick="sortTable(0)">
                        <div class="flex items-center space-x-1">
                            <span>Tanggal</span>
                            <i class="text-gray-400 fas fa-sort"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 transition-colors cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" onclick="sortTable(1)">
                        <div class="flex items-center space-x-1">
                            <span>Produk</span>
                            <i class="text-gray-400 fas fa-sort"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 transition-colors cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" onclick="sortTable(2)">
                        <div class="flex items-center space-x-1">
                            <span>Jumlah</span>
                            <i class="text-gray-400 fas fa-sort"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 transition-colors cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" onclick="sortTable(3)">
                        <div class="flex items-center space-x-1">
                            <span>Supplier</span>
                            <i class="text-gray-400 fas fa-sort"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Diproses Oleh</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                @forelse($transactions as $index => $transaction)
                    <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $index % 2 == 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-25 dark:bg-gray-825' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 mr-3 bg-blue-100 rounded-full dark:bg-blue-800">
                                    <i class="text-xs text-blue-600 fas fa-calendar dark:text-blue-300"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transaction->date)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 mr-3 bg-green-100 rounded-full dark:bg-green-800">
                                    <i class="text-xs text-green-600 fas fa-box dark:text-green-300"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($transaction->product)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        SKU: {{ optional($transaction->product)->sku ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="px-3 py-1 text-sm font-medium text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-800 dark:text-indigo-200">
                                    {{ number_format($transaction->quantity) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 mr-3 bg-purple-100 rounded-full dark:bg-purple-800">
                                    <i class="text-xs text-purple-600 fas fa-truck dark:text-purple-300"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($transaction->supplier)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($transaction->supplier)->email ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusClass = '';
                                $statusIcon = '';

                                if ($transaction->status == 'completed' || $transaction->status == 'diterima') {
                                    $statusClass = 'text-green-800 bg-green-100 border-green-200 dark:text-green-100 dark:bg-green-800 dark:border-green-700';
                                    $statusIcon = 'fas fa-check-circle';
                                } elseif ($transaction->status == 'pending') {
                                    $statusClass = 'text-yellow-800 bg-yellow-100 border-yellow-200 dark:text-yellow-100 dark:bg-yellow-800 dark:border-yellow-700';
                                    $statusIcon = 'fas fa-clock';
                                } else {
                                    $statusClass = 'text-gray-800 bg-gray-100 border-gray-200 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600';
                                    $statusIcon = 'fas fa-question-circle';
                                }
                            @endphp

                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium border rounded-full {{ $statusClass }}">
                                <i class="mr-1 {{ $statusIcon }}"></i>
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 mr-3 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <i class="text-xs text-gray-600 fas fa-user dark:text-gray-300"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($transaction->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($transaction->user)->role ?? 'Staff' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <i class="text-2xl text-gray-400 fas fa-inbox"></i>
                                </div>
                                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data</h3>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada transaksi yang dapat ditampilkan dalam laporan ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Enhanced Pagination -->
    @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                    <span>Menampilkan</span>
                    <span class="mx-1 font-medium">{{ $transactions->firstItem() }}</span>
                    <span>sampai</span>
                    <span class="mx-1 font-medium">{{ $transactions->lastItem() }}</span>
                    <span>dari</span>
                    <span class="mx-1 font-medium">{{ $transactions->total() }}</span>
                    <span>hasil</span>
                </div>
                <div class="flex items-center space-x-2">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Export menu toggle
function toggleExportMenu() {
    console.log('toggleExportMenu() dipanggil.'); // Debugging log
    const menu = document.getElementById('export-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    } else {
        console.error('Elemen dengan ID "export-menu" tidak ditemukan!');
    }
}

// Close export menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('export-menu');
    // Pastikan menu dan tombolnya ada sebelum melanjutkan
    if (!menu) return;

    const button = event.target.closest('button[onclick="toggleExportMenu()"]');

    if (!menu.classList.contains('hidden') && !button) {
        console.log('Menutup menu karena klik di luar.'); // Debugging log
        menu.classList.add('hidden');
    }
});

// ===================================================================
// == FUNGSI EXPORT YANG LEBIH BAIK UNTUK DEBUGGING ==
// ===================================================================
function exportToExcel() {
    try {
        console.log('Fungsi exportToExcel() dimulai...');

        // Ambil nilai dari semua filter
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const dateStart = document.getElementById('date-start').value;
        const dateEnd = document.getElementById('date-end').value;

        console.log('Filter yang didapat:', { search, status, dateStart, dateEnd });

        // Buat URLSearchParams untuk menampung parameter filter
        const params = new URLSearchParams();
        params.append('report_type', 'incoming_goods');
        params.append('format', 'excel');

        // Tambahkan filter jika ada nilainya
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (dateStart) params.append('date_start', dateStart);
        if (dateEnd) params.append('date_end', dateEnd);

        // Bangun URL lengkap dengan route dan parameter
        const url = `{{ route('staff.reports.export') }}?${params.toString()}`;

        console.log('URL yang akan diakses:', url); // <-- INI SANGAT PENTING

        // Arahkan browser ke URL untuk memulai download
        window.location.href = url;

        console.log('Proses download seharusnya sudah dimulai.');

        // Tutup menu dropdown setelah diklik
        toggleExportMenu();

    } catch (error) {
        console.error('Terjadi kesalahan di dalam fungsi exportToExcel:', error);
    }
}


// Fungsi lainnya (biarkan seperti semula)
function exportToPDF() {
    console.log('Exporting to PDF...');
    toggleExportMenu();
}

function printReport() {
    window.print();
    toggleExportMenu();
}

function refreshData() {
    const icon = document.getElementById('refresh-icon');
    icon.classList.add('fa-spin');
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// ... Sisa fungsi Anda (resetFilters, dll) biarkan seperti semula ...

</script>
@endpush
