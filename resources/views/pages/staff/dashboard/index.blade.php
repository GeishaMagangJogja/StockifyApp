{{-- File: resources/views/pages/staff/dashboard/index.blade.php --}}

@extends('layouts.dashboardstaff')

@section('title', 'Dashboard Tugas Staff Gudang')

@section('content')

    {{-- Header Halaman --}}
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Daftar Tugas Anda
    </h2>

    {{-- Notifikasi jika tidak ada tugas sama sekali --}}
    @if($incomingTasks->isEmpty() && $outgoingTasks->isEmpty())
        <div class="px-6 py-4 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Kerja bagus!
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Tidak ada tugas yang perlu diselesaikan saat ini.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Kontainer untuk dua kolom tugas --}}
    <div class="grid gap-8 md:grid-cols-2">

        {{-- Kolom 1: Tugas Barang Masuk --}}
        <div class="flex flex-col">
            <h3 class="mb-4 text-xl font-medium text-gray-800 dark:text-gray-100">
                Barang Masuk Perlu Diperiksa ({{ $incomingTasks->count() }})
            </h3>
            <div class="space-y-4">
                @forelse($incomingTasks as $task)
                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-100">
                                    {{ $task->product->name ?? 'Produk tidak ditemukan' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Jumlah: <span class="font-semibold">{{ $task->quantity }}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Dari: {{ $task->supplier->name ?? 'Supplier tidak spesifik' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                    Dibuat: {{ $task->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div>
                                {{-- Tombol ini akan mengarah ke halaman detail untuk konfirmasi --}}
                                <a href="#" class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Proses
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center bg-gray-50 rounded-lg dark:bg-gray-800">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada barang masuk yang perlu diperiksa.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Kolom 2: Tugas Barang Keluar --}}
        <div class="flex flex-col">
            <h3 class="mb-4 text-xl font-medium text-gray-800 dark:text-gray-100">
                Barang Keluar Perlu Disiapkan ({{ $outgoingTasks->count() }})
            </h3>
            <div class="space-y-4">
                @forelse($outgoingTasks as $task)
                    <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 border-l-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-100">
                                    {{ $task->product->name ?? 'Produk tidak ditemukan' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Jumlah: <span class="font-semibold">{{ $task->quantity }}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Catatan: {{ $task->notes ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                    Dibuat: {{ $task->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div>
                                {{-- Tombol ini akan mengarah ke halaman detail untuk konfirmasi --}}
                                <a href="#" class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Proses
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center bg-gray-50 rounded-lg dark:bg-gray-800">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada barang keluar yang perlu disiapkan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection

{{-- Tidak ada kode atau spasi apapun di bawah baris ini --}}
