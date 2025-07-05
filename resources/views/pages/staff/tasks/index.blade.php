@extends('layouts.dashboard')

@section('title', 'Pusat Pengerjaan Tugas')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Pusat Pengerjaan Tugas</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">Berikut adalah daftar semua tugas yang menunggu untuk diproses.</p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Layout Dua Kolom untuk Tugas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Kolom Tugas Barang Masuk -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-arrow-down mr-3"></i>
                    Tugas Barang Masuk (Pending)
                </h2>
                <p class="text-green-100 mt-1">{{ count($incomingTasks) }} tugas menunggu konfirmasi</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($incomingTasks as $task)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-700 border-l-4 border-green-400">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Qty: <span class="font-medium text-green-600">{{ $task->quantity }}</span> | Dari: {{ optional($task->supplier)->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            {{-- Tombol Proses --}}
                            <a href="{{ route('staff.tasks.incoming.confirm', $task->id) }}" title="Proses Tugas" class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                                <i class="fas fa-check"></i>
                            </a>
                            {{-- Tombol Tolak --}}
                            <form action="{{ route('staff.tasks.incoming.reject', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak tugas ini?');">
                                @csrf
                                <button type="submit" title="Tolak Tugas" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak Ada Tugas</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Semua tugas barang masuk sudah selesai.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Tugas Barang Keluar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-orange-500 to-red-600">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-arrow-up mr-3"></i>
                    Tugas Barang Keluar (Pending)
                </h2>
                <p class="text-orange-100 mt-1">{{ count($outgoingTasks) }} tugas perlu disiapkan</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($outgoingTasks as $task)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-700 border-l-4 border-orange-400">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Qty: <span class="font-medium text-orange-600">{{ $task->quantity }}</span>
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                             {{-- Tombol Siapkan --}}
                            <a href="{{ route('staff.tasks.outgoing.prepare', $task->id) }}" title="Siapkan Tugas" class="px-3 py-1.5 text-xs font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg">
                                <i class="fas fa-cogs"></i>
                            </a>
                             {{-- Tombol Tolak --}}
                            <form action="{{ route('staff.tasks.outgoing.reject', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak tugas ini?');">
                                @csrf
                                <button type="submit" title="Tolak Tugas" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak Ada Tugas</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Semua tugas barang keluar sudah selesai.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection