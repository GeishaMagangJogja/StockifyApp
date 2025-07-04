@extends('layouts.dashboard')

@section('title', 'Dashboard Tugas')

@section('content')
    {{-- Hero Section --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-800 dark:to-slate-700 rounded-2xl mb-8 p-8">
        <div class="relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
                        Selamat Datang Kembali, <span class="text-blue-600 dark:text-blue-400">{{ Auth::user()->name }}</span>!
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-300">
                        Ini adalah daftar tugas yang perlu Anda kerjakan hari ini.
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                </div>
            </div>
        </div>
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200 to-purple-200 dark:from-blue-600 dark:to-purple-600 rounded-full opacity-10 transform translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-br from-green-200 to-blue-200 dark:from-green-600 dark:to-blue-600 rounded-full opacity-10 transform -translate-x-16 translate-y-16"></div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 text-green-800 rounded-xl shadow-sm animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Card 1: Total Tugas Pending --}}
        <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
            <div class="relative p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium opacity-90 mb-1">Total Tugas Pending</p>
                        <p class="text-4xl font-bold mb-2">{{ number_format($totalPendingTasks) }}</p>
                        <div class="flex items-center text-xs opacity-75">
                            <i class="fas fa-clock mr-1"></i>
                            <span>Menunggu proses</span>
                        </div>
                    </div>
                    <div class="relative">
                        <i class="fas fa-inbox text-5xl opacity-30 group-hover:opacity-50 transition-opacity duration-300"></i>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-800">!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Tugas Masuk Hari Ini --}}
        <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 via-green-600 to-teal-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
            <div class="relative p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium opacity-90 mb-1">Tugas Masuk Hari Ini</p>
                        <p class="text-4xl font-bold mb-2">{{ number_format($incomingTodayCount) }}</p>
                        <div class="flex items-center text-xs opacity-75">
                            <i class="fas fa-arrow-trend-up mr-1"></i>
                            <span>Barang masuk</span>
                        </div>
                    </div>
                    <div class="relative">
                        <i class="fas fa-download text-5xl opacity-30 group-hover:opacity-50 transition-opacity duration-300"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Tugas Keluar Hari Ini --}}
        <div class="group relative overflow-hidden bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
            <div class="relative p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium opacity-90 mb-1">Tugas Keluar Hari Ini</p>
                        <p class="text-4xl font-bold mb-2">{{ number_format($outgoingTodayCount) }}</p>
                        <div class="flex items-center text-xs opacity-75">
                            <i class="fas fa-arrow-trend-down mr-1"></i>
                            <span>Barang keluar</span>
                        </div>
                    </div>
                    <div class="relative">
                        <i class="fas fa-upload text-5xl opacity-30 group-hover:opacity-50 transition-opacity duration-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Utama: Daftar Tugas & Widget --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Kolom Kiri: Daftar Tugas --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Tugas Barang Masuk --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6">
                    <h5 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-arrow-down mr-3 text-2xl"></i>
                        Tugas Barang Masuk
                    </h5>
                    <p class="text-green-100 mt-1">{{ count($incomingTasks) }} tugas menunggu konfirmasi</p>
                </div>
                <div class="p-6">
                    @forelse ($incomingTasks as $index => $task)
                        <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all duration-200 border-l-4 border-green-400 mb-4 group">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                        {{ $task->product->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Qty: <span class="font-medium text-green-600">{{ $task->quantity }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                        <i class="fas fa-truck mr-1"></i>
                                        Dari: {{ $task->supplier->name ?? 'N/A' }} • {{ $task->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="text-right mr-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Prioritas</div>
                                    <div class="text-sm font-medium text-green-600">Normal</div>
                                </div>
                                <a href="{{ route('staff.stock.incoming.confirm', $task->id) }}" 
                                   class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="fas fa-check mr-1"></i>
                                    Proses
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak ada tugas barang masuk</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Semua tugas telah selesai diproses</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tugas Barang Keluar --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6">
                    <h5 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-arrow-up mr-3 text-2xl"></i>
                        Tugas Barang Keluar
                    </h5>
                    <p class="text-orange-100 mt-1">{{ count($outgoingTasks) }} tugas perlu disiapkan</p>
                </div>
                <div class="p-6">
                    @forelse ($outgoingTasks as $task)
                        <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all duration-200 border-l-4 border-orange-400 mb-4 group">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shipping-fast text-orange-600 dark:text-orange-400"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                        {{ $task->product->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Qty: <span class="font-medium text-orange-600">{{ $task->quantity }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        Catatan: {{ Str::limit($task->notes, 30) ?? '-' }} • {{ $task->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="text-right mr-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Status</div>
                                    <div class="text-sm font-medium text-orange-600">Pending</div>
                                </div>
                                <a href="{{ route('staff.stock.outgoing.prepare', $task->id) }}" 
                                   class="px-4 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="fas fa-cogs mr-1"></i>
                                    Siapkan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-truck text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak ada tugas barang keluar</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Semua tugas telah selesai diproses</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- Kolom Kanan: Widget Samping --}}
        <div class="space-y-8">
            {{-- Widget Stok Akan Habis --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 p-6">
                    <h5 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Stok Akan Habis
                    </h5>
                    <p class="text-red-100 mt-1">{{ count($lowStockProducts) }} produk perlu perhatian</p>
                </div>
                <div class="p-6">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex justify-between items-center p-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-red-600 dark:text-red-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Min: {{ $product->min_stock }} {{ $product->unit }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-red-500">{{ $product->current_stock }}</span>
                                <div class="text-xs text-gray-500 dark:text-gray-400">tersisa</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Semua stok dalam batas aman</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Widget Aktivitas Terbaru --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6">
                    <h5 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-history mr-3"></i>
                        Aktivitas Terbaru
                    </h5>
                    <p class="text-purple-100 mt-1">{{ count($recentTransactions) }} transaksi selesai</p>
                </div>
                <div class="p-6">
                    @forelse ($recentTransactions as $transaction)
                        <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors duration-200 mb-3">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full {{ $transaction->type == 'Masuk' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
                                <i class="fas {{ $transaction->type == 'Masuk' ? 'fa-arrow-down text-green-600 dark:text-green-400' : 'fa-arrow-up text-red-600 dark:text-red-400' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $transaction->product->name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->type }} • {{ $transaction->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'Masuk' ? '+' : '-' }}{{ $transaction->quantity ?? 0 }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada aktivitas selesai</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection