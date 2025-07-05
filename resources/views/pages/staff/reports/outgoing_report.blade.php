@extends('layouts.dashboard')

@section('title', 'Laporan Riwayat Barang Keluar')

@section('content')
<!-- Enhanced Header Section -->
<div class="mb-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-sm">
            <li class="inline-flex items-center">
                {{-- Sesuaikan dengan rute dashboard Anda, contoh untuk admin --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center text-gray-500 transition-colors hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                    <i class="w-4 h-4 mr-2 fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="w-4 h-4 text-gray-400 fas fa-chevron-right"></i>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Laporan Barang Keluar</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with Stats -->
    <div class="relative overflow-hidden p-9 bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 rounded-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-16 h-16 mr-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <i class="text-2xl text-white fas fa-truck-loading"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Laporan Barang Keluar</h1>
                        <p class="text-red-100">Riwayat lengkap pengeluaran barang dari inventori</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="refreshData()"
                            class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white rounded-lg bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30">
                        <i class="mr-2 fas fa-sync-alt" id="refresh-icon"></i>
                        Refresh
                    </button>
                    <div class="relative">
                        <button onclick="toggleExportMenu()"
                                class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white rounded-lg bg-opacity-20 backdrop-blur-sm hover:bg-opacity-30">
                            <i class="mr-2 fas fa-download"></i>
                            Export
                            <i class="ml-2 fas fa-chevron-down"></i>
                        </button>
                        <!-- Export Dropdown -->
                        <div id="export-menu" class="absolute right-0 z-10 hidden w-48 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                            <div class="py-2">
                                <a href="#" onclick="exportToExcel()" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
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
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg dark:bg-red-800">
                <i class="text-red-600 fas fa-truck-loading dark:text-red-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Keluar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->total() }}</p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg dark:bg-green-800">
                <i class="text-green-600 fas fa-check-circle dark:text-green-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->whereIn('status', ['completed', 'dikeluarkan'])->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg dark:bg-orange-800">
                <i class="text-orange-600 fas fa-clock dark:text-orange-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow duration-300 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg dark:bg-purple-800">
                <i class="text-purple-600 fas fa-calendar-day dark:text-purple-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('date', today()->toDateString())->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ======================================= -->
<!-- BAGIAN FILTER YANG SUDAH DIPERBAIKI -->
<!-- ======================================= -->
<div class="p-6 mb-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter & Pencarian</h3>
        <a href="{{ route('staff.reports.outgoing') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
            <i class="mr-1 fas fa-undo"></i>Reset Filter
        </a>
    </div>
    
    <form action="{{ route('staff.reports.outgoing') }}" method="GET">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
            <div class="md:col-span-2">
                <label for="search-input" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                <div class="relative">
                    <i class="absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2 fas fa-search"></i>
                    <input type="text" name="search" id="search-input" placeholder="Cari produk, tujuan..." value="{{ request('search') }}"
                           class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div>
                <label for="status-filter" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="dikeluarkan" {{ request('status') == 'dikeluarkan' ? 'selected' : '' }}>Selesai (Dikeluarkan)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label for="date-start" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                <input type="date" name="date_start" id="date-start" value="{{ request('date_start') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label for="date-end" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                <input type="date" name="date_end" id="date-end" value="{{ request('date_end') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        <div class="mt-4 text-right">
            <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600">
                <i class="mr-2 fas fa-filter"></i> Terapkan
            </button>
        </div>
    </form>
</div>


<!-- Enhanced Table -->
<div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="text-gray-500 fas fa-table"></i>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Barang Keluar</h3>
                <span class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-200">
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
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3 text-center">Jumlah</th>
                    <th class="px-6 py-3">Catatan/Tujuan</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Diproses Oleh</th>
                </tr>
            </thead>
            <!-- ======================================= -->
            <!-- BAGIAN TBODY YANG SUDAH DIPERBAIKI -->
            <!-- ======================================= -->
            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                @forelse($transactions as $transaction)
                    <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{-- Kolom Tanggal --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-200">{{ \Carbon\Carbon::parse($transaction->date)->isoFormat('D MMMM YYYY') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i') }} WIB</div>
                        </td>

                        {{-- Kolom Produk --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="flex items-center justify-center w-10 h-10 font-bold text-white bg-red-500 rounded-full">
                                        {{ substr(optional($transaction->product)->name ?? 'P', 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ optional($transaction->product)->name ?? 'Produk Dihapus' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        SKU: {{ optional($transaction->product)->sku ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Jumlah --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-sm font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                {{ $transaction->quantity }}
                            </span>
                        </td>
                        
                        {{-- Kolom Catatan/Tujuan --}}
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $transaction->notes ?? '-' }}
                        </td>
                        
                        {{-- Kolom Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                             @if($transaction->status == 'completed' || $transaction->status == 'dikeluarkan')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                    <i class="mr-1 fas fa-check-circle"></i> Selesai
                                </span>
                            @elseif($transaction->status == 'pending')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                    <i class="mr-1 fas fa-clock"></i> Pending
                                </span>
                            @elseif($transaction->status == 'rejected' || $transaction->status == 'ditolak')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                    <i class="mr-1 fas fa-times-circle"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full dark:bg-gray-600 dark:text-gray-100">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Diproses Oleh --}}
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">
                            {{ optional($transaction->user)->name ?? 'Sistem' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <i class="text-2xl text-gray-400 fas fa-inbox"></i>
                                </div>
                                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data ditemukan</h3>
                                <p class="text-gray-500 dark:text-gray-400">Coba ubah filter pencarian Anda atau belum ada transaksi barang keluar.</p>
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
            {{ $transactions->appends(request()->query())->links() }}
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
