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
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Laporan</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with Stats -->
    <div class="relative p-6 overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-16 h-16 mr-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                        <i class="text-2xl text-white fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $reportTitle }}</h1>
                        <p class="text-indigo-100">Laporan lengkap transaksi dan aktivitas sistem</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="refreshData()" 
                            class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm hover:bg-opacity-30">
                        <i class="mr-2 fas fa-sync-alt" id="refresh-icon"></i>
                        Refresh
                    </button>
                    <div class="relative">
                        <button onclick="toggleExportMenu()" 
                                class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm hover:bg-opacity-30">
                            <i class="mr-2 fas fa-download"></i>
                            Export
                            <i class="ml-2 fas fa-chevron-down"></i>
                        </button>
                        <!-- Export Dropdown -->
                        <div id="export-menu" class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-lg shadow-lg focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-600" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
        <!-- ITEM 1: PDF -->
        <!-- ITEM 2: EXCEL -->
        <a href="#" onclick="exportToExcel(event)" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">
            <i class="w-5 mr-3 text-green-500 fas fa-file-excel"></i>
            <span>Export ke Excel</span>
        </a>
    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white opacity-5 rounded-full"></div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
    <div class="p-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg dark:bg-blue-800">
                <i class="text-blue-600 fas fa-boxes dark:text-blue-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactions->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg dark:bg-green-800">
                <i class="text-green-600 fas fa-check-circle dark:text-green-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('status', 'completed')->count() + $transactions->where('status', 'diterima')->count() }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg dark:bg-yellow-800">
                <i class="text-yellow-600 fas fa-clock dark:text-yellow-300"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $transactions->where('status', '!=', 'completed')->where('status', '!=', 'diterima')->count() }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg dark:bg-purple-800">
                <i class="text-purple-600 fas fa-calendar-day dark:text-purple-300"></i>
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
<div class="p-6 mb-6 bg-white border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter & Pencarian</h3>
        <button onclick="resetFilters()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
            <i class="mr-1 fas fa-undo"></i>Reset Filter
        </button>
    </div>
    
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
            <div class="relative">
                <i class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Cari produk, supplier..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Status</option>
                <option value="completed">Completed</option>
                <option value="diterima">Diterima</option>
                <option value="pending">Pending</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
            <input type="date" id="date-start" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
            <input type="date" id="date-end" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
    </div>
</div>

<!-- Enhanced Table -->
<div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
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
            
            <div class="flex items-center space-x-2">
                <button onclick="toggleTableView()" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-th-list" id="view-toggle-icon"></i>
                </button>
                <button onclick="toggleFullscreen()" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-expand" id="fullscreen-icon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto" id="table-container">
        <table class="w-full" id="data-table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="sortTable(0)">
                        <div class="flex items-center space-x-1">
                            <span>Tanggal</span>
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="sortTable(1)">
                        <div class="flex items-center space-x-1">
                            <span>Produk</span>
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="sortTable(2)">
                        <div class="flex items-center space-x-1">
                            <span>Jumlah</span>
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="sortTable(3)">
                        <div class="flex items-center space-x-1">
                            <span>Supplier</span>
                            <i class="fas fa-sort text-gray-400"></i>
                        </div>
                    </th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Diproses Oleh</th>
                    <th class="px-6 py-4">Aksi</th>
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
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="viewDetails({{ $transaction->id }})" 
                                        class="p-2 text-blue-600 transition-colors hover:text-blue-800 hover:bg-blue-50 rounded-lg dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/50">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editTransaction({{ $transaction->id }})" 
                                        class="p-2 text-green-600 transition-colors hover:text-green-800 hover:bg-green-50 rounded-lg dark:text-green-400 dark:hover:text-green-300 dark:hover:bg-green-900/50">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTransaction({{ $transaction->id }})" 
                                        class="p-2 text-red-600 transition-colors hover:text-red-800 hover:bg-red-50 rounded-lg dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <i class="text-2xl text-gray-400 fas fa-inbox"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data</h3>
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