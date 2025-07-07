@extends('layouts.dashboard')

@section('title', 'Laporan Arus Barang')

@push('styles')
<style>
    /* ... CSS tidak ada perubahan ... */
    .sortable-header { 
        cursor: pointer; 
        transition: all 0.3s ease; 
        position: relative;
    }
    .sortable-header:hover { 
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(139, 69, 19, 0.05) 100%);
        transform: translateY(-1px);
    }
    .dark .sortable-header:hover { 
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(139, 69, 19, 0.08) 100%);
    }
    .sort-icon { 
        transition: all 0.3s ease; 
    }
    a:hover .sort-icon { 
        color: #06b6d4; 
        transform: scale(1.1);
    }
    
    .glass-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dark .glass-card {
        background: rgba(30, 41, 59, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .transaction-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .transaction-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .transaction-card.incoming {
        border-left-color: #10b981;
    }
    
    .transaction-card.outgoing {
        border-left-color: #ef4444;
    }
    
    .animated-counter {
        background: linear-gradient(45deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: 200px 0; }
    }
    
    .filter-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dark .filter-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(51, 65, 85, 0.6) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .search-input {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
        transform: scale(1.02);
    }
    
    .dark .search-input {
        background: rgba(30, 41, 59, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    
    .pulse-dot {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Header, Stats, Filter ... tidak ada perubahan di sini -->
        <div class="relative mb-8 overflow-hidden bg-white shadow-2xl rounded-3xl dark:bg-slate-800">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 opacity-10"></div>
            <div class="relative p-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-2xl">
                            <i class="text-2xl text-white fas fa-exchange-alt"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                                Audit Arus Barang
                                <span class="inline-block w-3 h-3 ml-2 bg-green-500 rounded-full pulse-dot"></span>
                            </h1>
                            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                                Monitoring real-time pergerakan inventori dengan analisis mendalam
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('manajergudang.reports.transactions') }}" 
                       class="flex items-center px-6 py-3 text-white transition-all duration-300 transform bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl hover:from-gray-700 hover:to-gray-800 hover:scale-105 hover:shadow-lg">
                        <i class="mr-3 fas fa-sync-alt"></i>
                        Reset Filter
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-slate-800 group">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400 to-blue-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transaksi</p>
                            <p class="text-3xl font-bold text-gray-900 animated-counter dark:text-white">
                                {{ number_format($stats['total']) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl">
                            <i class="text-xl text-white fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-slate-800 group">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Transaksi Masuk</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($stats['incoming']) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl">
                            <i class="text-xl text-white fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-slate-800 group">
                <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-pink-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Transaksi Keluar</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($stats['outgoing']) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl">
                            <i class="text-xl text-white fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-slate-800 group">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-indigo-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Unit</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format($stats['total_quantity']) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl">
                            <i class="text-xl text-white fas fa-cubes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 overflow-hidden bg-white shadow-2xl rounded-3xl dark:bg-slate-800">
            <div class="bg-gradient-to-r from-cyan-500 to-purple-600 p-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="mr-3 fas fa-filter"></i>
                    Filter & Pencarian Advanced
                </h3>
                <p class="text-cyan-100">Gunakan filter untuk analisis data yang lebih spesifik</p>
            </div>
            <div class="p-6">
                <form action="{{ route('manajergudang.reports.transactions') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="mr-2 fas fa-search"></i>Pencarian Global
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari produk, user, supplier, atau catatan..."
                                   class="search-input w-full px-4 py-3 rounded-xl focus:outline-none text-gray-900 dark:text-white placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="mr-2 fas fa-tags"></i>Tipe Transaksi
                            </label>
                            <select name="type" class="search-input w-full px-4 py-3 rounded-xl focus:outline-none text-gray-900 dark:text-white">
                                <option value="">🔄 Semua Tipe</option>
                                <option value="Masuk" @selected(request('type') == 'Masuk')>📥 Masuk</option>
                                <option value="Keluar" @selected(request('type') == 'Keluar')>📤 Keluar</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-gradient self-end flex items-center justify-center w-full px-6 py-3 font-bold text-white rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-300">
                            <i class="mr-3 fas fa-search"></i>Analisis Data
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="mr-2 fas fa-calendar-alt"></i>Dari Tanggal
                            </label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                   class="search-input w-full px-4 py-3 rounded-xl focus:outline-none text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="mr-2 fas fa-calendar-alt"></i>Sampai Tanggal
                            </label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                   class="search-input w-full px-4 py-3 rounded-xl focus:outline-none text-gray-900 dark:text-white">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden bg-white shadow-2xl rounded-3xl dark:bg-slate-800">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="mr-3 fas fa-table"></i>
                    Data Transaksi Detail
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-700 dark:to-slate-600 dark:text-gray-400">
                        {{-- Tabel header tidak ada perubahan --}}
                        <tr>
                            @php
                                function getSortLink($column, $sortBy, $sortDirection) {
                                    $direction = ($sortBy == $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                                    return request()->fullUrlWithQuery(['sort_by' => $column, 'direction' => $direction]);
                                }
                            @endphp
                            <th scope="col" class="px-6 py-4 w-1/4 sortable-header">
                                <a href="{{ getSortLink('date', $sortBy, $sortDirection) }}" class="flex items-center font-bold">
                                    <i class="mr-2 fas fa-calendar-alt"></i>
                                    TANGGAL & WAKTU
                                    <i class="ml-2 text-gray-400 fas sort-icon @if($sortBy == 'date') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 w-1/3 sortable-header">
                                <a href="{{ getSortLink('product_name', $sortBy, $sortDirection) }}" class="flex items-center font-bold">
                                    <i class="mr-2 fas fa-box"></i>
                                    DETAIL PRODUK & TRANSAKSI
                                    <i class="ml-2 text-gray-400 fas sort-icon @if($sortBy == 'product_name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 w-1/4 sortable-header">
                                <a href="{{ getSortLink('user_name', $sortBy, $sortDirection) }}" class="flex items-center font-bold">
                                    <i class="mr-2 fas fa-user-tie"></i>
                                    STAFF BERTUGAS
                                    <i class="ml-2 text-gray-400 fas sort-icon @if($sortBy == 'user_name') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-4 text-right sortable-header">
                                <a href="{{ getSortLink('quantity', $sortBy, $sortDirection) }}" class="inline-flex items-center font-bold">
                                    <i class="mr-2 fas fa-sort-numeric-up"></i>
                                    JUMLAH
                                    <i class="ml-2 text-gray-400 fas sort-icon @if($sortBy == 'quantity') fa-sort-{{ $sortDirection }} @else fa-sort @endif"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            {{-- PERUBAHAN --}}
                            <tr class="transaction-card bg-white border-b dark:bg-slate-800 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 {{ $transaction->isTypeMasuk() ? 'incoming' : 'outgoing' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- PERUBAHAN --}}
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $transaction->isTypeMasuk() ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
                                            <i class="text-sm {{ $transaction->isTypeMasuk() ? 'text-green-600 dark:text-green-400 fas fa-arrow-down' : 'text-red-600 dark:text-red-400 fas fa-arrow-up' }}"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $transaction->date->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('H:i:s') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <div class="font-bold text-gray-900 dark:text-white flex items-center">
                                            <i class="mr-2 fas fa-box text-gray-400"></i>
                                            {{ $transaction->product->name ?? 'Produk Dihapus' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{-- PERUBAHAN --}}
                                            @if($transaction->isTypeMasuk() && $transaction->supplier)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                    <i class="mr-1 fas fa-truck"></i>
                                                    {{ $transaction->supplier->name }}
                                                </span>
                                            @elseif($transaction->notes)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                    <i class="mr-1 fas fa-comment-alt"></i>
                                                    {{ Str::limit($transaction->notes, 40) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full">
                                            <i class="text-sm text-white fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $transaction->user->name ?? 'Sistem' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->user->role ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- PERUBAHAN --}}
                                    <div class="text-2xl font-bold {{ $transaction->isTypeMasuk() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->isTypeMasuk() ? '+' : '-' }} {{ number_format($transaction->quantity) }}
                                        <span class="block text-xs font-normal text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $transaction->product->unit ?? 'unit' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <div class="flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full dark:bg-slate-700">
                                            <i class="text-4xl text-gray-400 fas fa-search"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Transaksi Tidak Ditemukan</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Silakan sesuaikan filter pencarian Anda untuk menemukan data yang relevan.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-700 dark:to-slate-600">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection