@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Header Section with Glass Effect -->
    <div class="relative mb-8 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl backdrop-blur-sm"></div>
        <div class="relative p-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center mb-4 space-x-2 text-sm">
                <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                    <i class="mr-2 fas fa-tachometer-alt"></i>Dashboard
                </span>
            </nav>

            <!-- Title Section -->
            <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-tachometer-alt dark:text-blue-400"></i>
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Ringkasan aktivitas sistem hari ini
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.reports.stock') }}"
                       class="relative inline-flex items-center px-6 py-3 text-gray-700 transition-all duration-300 bg-white shadow group rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 hover:shadow-md">
                        <i class="mr-2 fas fa-file-alt"></i>
                        <span>Lihat Laporan</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="group relative inline-flex items-center px-6 py-3 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                        <i class="mr-2 fas fa-plus"></i>
                        <span class="font-medium">Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    @if(session('success'))
    <div class="p-4 mb-6 text-green-800 bg-green-100 rounded-xl dark:bg-green-900/50 dark:text-green-300">
        <i class="mr-2 fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 mb-6 text-red-800 bg-red-100 rounded-xl dark:bg-red-900/50 dark:text-red-300">
        <i class="mr-2 fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Products Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-green-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                        <i class="text-xl text-white fas fa-box"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full dark:text-green-400 dark:bg-green-900/50">
                        Produk
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Suppliers Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                        <i class="text-xl text-white fas fa-truck"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900/50">
                        Supplier
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Supplier</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSuppliers ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-indigo-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl">
                        <i class="text-xl text-white fas fa-tags"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-full dark:text-indigo-400 dark:bg-indigo-900/50">
                        Kategori
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kategori</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCategories ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-purple-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                        <i class="text-xl text-white fas fa-users"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-purple-600 bg-purple-100 rounded-full dark:text-purple-400 dark:bg-purple-900/50">
                        Pengguna
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left Column - Charts and Low Stock -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Chart Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-chart-line dark:text-blue-400"></i>
                            Aktivitas Transaksi (7 Hari Terakhir)
                        </h2>
                    </div>
                </div>
                <div class="p-6">
                    <div id="main-chart"></div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-red-600 fas fa-exclamation-triangle dark:text-red-400"></i>
                            Stok Akan Habis
                        </h2>
                    </div>
                </div>
                <div class="p-6">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0 dark:border-gray-700">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $product->min_stock }} {{ $product->unit }}</p>
                            </div>
                            <span class="text-base font-bold text-red-600 dark:text-red-400">{{ $product->current_stock }}</span>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <div class="mx-auto mb-4 text-5xl text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">Semua stok dalam batas aman</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Recent Activities -->
        <div class="space-y-6">
            {{-- BLOK INI TELAH DIPERBAIKI --}}
            <!-- Recent Transactions Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-exchange-alt dark:text-blue-400"></i>
                            Transaksi Terbaru
                        </h2>
                    </div>
                </div>
                <div class="p-6">
                    @forelse ($recentTransactions as $transaction)
                        <div class="flex items-center py-3 space-x-4 border-b border-gray-200 last:border-0 dark:border-gray-700">
                            <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full {{ $transaction->isTypeMasuk() ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                                <i class="fas {{ $transaction->isTypeMasuk() ? 'fa-arrow-down text-green-600 dark:text-green-400' : 'fa-arrow-up text-red-600 dark:text-red-400' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ optional($transaction->product)->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->isTypeMasuk() ? 'Masuk' : 'Keluar' }} • {{ $transaction->date->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-sm font-semibold {{ $transaction->isTypeMasuk() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->isTypeMasuk() ? '+' : '-' }}{{ number_format($transaction->quantity) }}
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <i class="mx-auto mb-4 text-5xl text-gray-400 fas fa-exchange-alt"></i>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Users Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-user-plus dark:text-blue-400"></i>
                            Pengguna Baru
                        </h2>
                    </div>
                </div>
                <div class="p-6">
                    @forelse ($recentUsers as $user)
                        <div class="flex items-center py-3 space-x-4 border-b border-gray-200 last:border-0 dark:border-gray-700">
                            <img class="object-cover w-10 h-10 rounded-full" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="{{ $user->name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->role }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <i class="mx-auto mb-4 text-5xl text-gray-400 fas fa-user-slash"></i>
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada pengguna baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = {!! json_encode($chartData) !!};
            const options = {
                series: [
                    {
                        name: 'Barang Masuk',
                        data: chartData.incoming,
                        color: '#22c55e'
                    },
                    {
                        name: 'Barang Keluar',
                        data: chartData.outgoing,
                        color: '#ef4444'
                    }
                ],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: chartData.categories,
                    labels: {
                        style: {
                            colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: document.documentElement.classList.contains('dark') ? '#4B5563' : '#E5E7EB',
                    strokeDashArray: 4
                },
                tooltip: {
                    y: { formatter: (val) => `${val} unit` },
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    labels: {
                        colors: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                    }
                }
            };
            const chart = new ApexCharts(document.querySelector("#main-chart"), options);
            chart.render();
        });
    </script>
@endpush