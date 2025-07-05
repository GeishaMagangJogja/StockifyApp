@extends('layouts.dashboard')

@section('title', 'Persiapan Barang Keluar')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Header dengan ikon -->
    <div class="flex items-center mb-8">
        <div class="p-3 mr-4 text-white bg-red-500 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Persiapan Pengeluaran Barang</h2>
            <p class="text-gray-600 dark:text-gray-400">Konfirmasi pengeluaran barang dari gudang</p>
        </div>
    </div>

    <!-- Card utama -->
    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <!-- Notifikasi -->
        @if (session('warning'))
        <div class="flex items-center p-4 mb-6 text-yellow-800 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg dark:bg-yellow-900/30 dark:text-yellow-200">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('warning') }}</p>
        </div>
        @endif

        @if (session('error'))
        <div class="flex items-center p-4 mb-6 text-red-800 bg-red-100 border-l-4 border-red-500 rounded-lg dark:bg-red-900/30 dark:text-red-200">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <!-- Card detail tugas -->
        <div class="p-5 mb-6 border border-gray-200 rounded-xl dark:border-gray-700">
            <div class="flex items-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detail Tugas</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Kolom kiri -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Produk</p>
                        <p class="font-medium text-gray-800 dark:text-gray-100">
                            {{ optional($task->product)->sku ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Kolom kanan -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Saat Ini</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ optional($task->product)->current_stock ?? 0 }} unit
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Diminta</p>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ $task->quantity }} unit
                        </p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            @if($task->notes)
            <div class="p-3 mt-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Permintaan</p>
                <p class="text-gray-800 dark:text-gray-200">{{ $task->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Form pengeluaran -->
        <div class="p-5 border border-gray-200 rounded-xl dark:border-gray-700">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Form Pengeluaran</h3>
            </div>

            <form action="{{ route('staff.stock.outgoing.dispatch', $task) }}" method="POST">
                @csrf

                <!-- Input jumlah -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Jumlah Aktual Dikeluarkan
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="quantity_dispatched"
                            class="w-full px-4 py-3 text-sm transition-colors border border-gray-300 rounded-lg dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="Masukkan jumlah yang dikeluarkan"
                            value="{{ old('quantity_dispatched', min($task->quantity, optional($task->product)->current_stock ?? 0)) }}"
                            required
                            min="1"
                            max="{{ optional($task->product)->current_stock ?? 0 }}"
                            oninput="this.value = Math.max(1, Math.min(parseInt(this.value) || 0, {{ optional($task->product)->current_stock ?? 0 }}))"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-sm text-gray-500 dark:text-gray-400">unit</span>
                        </div>
                    </div>
                    @error('quantity_dispatched')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input catatan -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Catatan Tambahan
                        <span class="text-xs text-gray-500">(Opsional)</span>
                    </label>
                    <textarea
                        name="dispatch_notes"
                        rows="3"
                        class="w-full px-4 py-2 text-sm text-gray-900 border-gray-300 rounded-lg resize-none bg-gray-50 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                        placeholder="Tambahkan catatan jika diperlukan (kondisi barang, lokasi tujuan, dll)"
                    >{{ old('dispatch_notes') }}</textarea>
                </div>

                <!-- Tombol aksi -->
                <div class="flex justify-end pt-4 mt-6 space-x-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('staff.tasks.index') }}" class="px-5 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Kembali
                    </a>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-1 -mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Konfirmasi Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
