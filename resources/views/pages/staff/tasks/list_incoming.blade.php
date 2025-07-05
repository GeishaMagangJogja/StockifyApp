@extends('layouts.dashboard')

@section('title', 'Daftar Barang Masuk')

@section('content')
    {{-- Header dengan Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 hover:shadow-md" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors duration-300">
                        <i class="fas fa-tachometer-alt w-4 h-4 mr-2.5"></i>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right w-3 h-3 text-gray-400 mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Daftar Barang Masuk</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Pesan Sukses dengan Animasi --}}
    @if (session('success'))
        <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-green-500 rounded-lg shadow-lg transform transition-all duration-500 hover:scale-105" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-red-500 rounded-lg shadow-lg transform transition-all duration-500 hover:scale-105" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Card Utama --}}
    <div class="w-full p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-lg">
        {{-- Header Card --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 p-3 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                <i class="fas fa-box-open mr-2 text-blue-600"></i>
                Riwayat & Daftar Barang Masuk
            </h2>
            <div class="flex items-center space-x-2 mt-2 md:mt-0">
                <span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ $transactions->total() }} item</span>
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
        </div>

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('staff.tasks.incoming.list') }}" method="GET" class="transition-all duration-300">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                {{-- Kolom Pencarian --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <label for="search" class="sr-only">Cari</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300"
                               placeholder="Cari produk berdasarkan nama atau SKU..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Dropdown Filter Status --}}
                <div>
                    <label for="status" class="sr-only">Filter Status</label>
                    <select name="status" id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300">
                        <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Tombol Aksi Filter --}}
                <div>
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel Data --}}
        <div class="w-full overflow-hidden rounded-lg shadow">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <th class="px-4 py-3">
                                <i class="fas fa-box mr-1"></i>
                                Produk
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-sort-numeric-up mr-1"></i>
                                Jumlah
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-truck mr-1"></i>
                                Supplier
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-flag mr-1"></i>
                                Status
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-calendar mr-1"></i>
                                Tanggal
                            </th>
                            <th class="px-4 py-3">
                                <i class="fas fa-cog mr-1"></i>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($transactions as $transaction)
                            <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                {{-- Kolom Produk --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3 text-xs">
                                            {{ substr(optional($transaction->product)->name ?? 'P', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ optional($transaction->product)->name ?? 'Produk Dihapus' }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                SKU: {{ optional($transaction->product)->sku ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Kolom Jumlah --}}
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                        {{ $transaction->quantity }}
                                    </span>
                                </td>
                                
                                {{-- Kolom Supplier --}}
                                <td class="px-4 py-3 text-sm">
                                    {{ optional($transaction->supplier)->name ?? optional($transaction->product->supplier)->name ?? 'N/A' }}
                                </td>
                                
                                {{-- Kolom Status --}}
                                <td class="px-4 py-3">
                                    @if($transaction->status == 'completed')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Selesai
                                        </span>
                                    @elseif($transaction->status == 'pending')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    @elseif($transaction->status == 'rejected')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-900 dark:text-gray-300">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Kolom Tanggal --}}
                                <td class="px-4 py-3 text-sm">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                                </td>
                                
                                {{-- =================================== --}}
                                {{-- == PERBAIKAN KOLOM AKSI DI SINI == --}}
                                {{-- =================================== --}}
                                <td class="px-4 py-3">
                                    @if($transaction->status == 'pending')
                                        <div class="flex items-center space-x-2">
                                            {{-- Tombol Proses/Selesaikan --}}
                                            <a href="{{ route('staff.tasks.incoming.confirm', $transaction) }}" 
                                               title="Proses Tugas"
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-check mr-1"></i>
                                                Proses
                                            </a>
                                            
                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('staff.tasks.incoming.reject', $transaction) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak tugas ini?');">
                                                @csrf
                                                <button type="submit" 
                                                        title="Tolak Tugas"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Data tidak ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm">Coba ubah kata kunci pencarian atau filter</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2 sm:mb-0">
                        <span>Menampilkan {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} item</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide success message
        const successAlert = document.querySelectorAll('[role="alert"]');
        if (successAlert) {
            successAlert.forEach(function(alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        }
    </script>
@endsection