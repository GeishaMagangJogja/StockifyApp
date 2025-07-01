@extends('layouts.dashboard')

@section('title', 'Dashboard Tugas')

@section('content')
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Ini adalah daftar tugas yang perlu Anda kerjakan hari ini.</p>
        </div>
    </div>
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Baris Pertama: Kartu Statistik --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Total Tugas Pending</p>
                    <p class="text-3xl font-bold">{{ number_format($totalPendingTasks) }}</p>
                </div>
                <i class="fas fa-inbox text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="p-6 bg-gradient-to-br from-sky-500 to-sky-600 rounded-lg shadow-lg text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Tugas Masuk Hari Ini</p>
                    <p class="text-3xl font-bold">{{ number_format($incomingTodayCount) }}</p>
                </div>
                <i class="fas fa-arrow-circle-down text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="p-6 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium opacity-80">Tugas Keluar Hari Ini</p>
                    <p class="text-3xl font-bold">{{ number_format($outgoingTodayCount) }}</p>
                </div>
                <i class="fas fa-arrow-circle-up text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    {{-- Kolom Utama: Daftar Tugas & Widget --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Daftar Tugas --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Tugas Barang Masuk --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Tugas Barang Masuk</h5>
                @forelse ($incomingTasks as $task)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $task->product->name }} (x{{ $task->quantity }})</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dari: {{ $task->supplier->name ?? 'N/A' }} • {{ $task->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('staff.stock.incoming.confirm', $task->id) }}" class="px-3 py-1 text-sm bg-green-500 text-white rounded-full hover:bg-green-600">Proses</a>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Tidak ada tugas barang masuk.</p>
                @endforelse
            </div>

            {{-- Tugas Barang Keluar --}}
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Tugas Barang Keluar</h5>
                @forelse ($outgoingTasks as $task)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                         <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $task->product->name }} (x{{ $task->quantity }})</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Catatan: {{ Str::limit($task->notes, 30) ?? '-' }} • {{ $task->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('staff.stock.outgoing.prepare', $task->id) }}" class="px-3 py-1 text-sm bg-orange-500 text-white rounded-full hover:bg-orange-600">Siapkan</a>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Tidak ada tugas barang keluar.</p>
                @endforelse
            </div>
        </div>
        
        {{-- Kolom Kanan: Widget Samping --}}
        <div class="space-y-6">
            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Stok Akan Habis</h5>
                @forelse ($lowStockProducts as $product)
                    <div class="flex justify-between items-center py-2">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $product->min_stock }} {{ $product->unit }}</p>
                        </div>
                        <span class="text-base font-bold text-red-500">{{ $product->current_stock }}</span>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Semua stok dalam batas aman.</p>
                @endforelse
            </div>

            <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Aktivitas Selesai Terbaru</h5>
                @forelse ($recentTransactions as $transaction)
                    <div class="flex items-center space-x-3 py-2">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $transaction->type == 'Masuk' ? 'bg-green-100' : 'bg-red-100' }}">
                            <i class="fas {{ $transaction->type == 'Masuk' ? 'fa-arrow-down text-green-500' : 'fa-arrow-up text-red-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ $transaction->product->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">Belum ada aktivitas selesai.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection