@extends('layouts.dashboardstaff')

@section('title', 'Persiapan Barang Keluar')

@section('content')
    <h2 class="mb-4 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Persiapan Pengeluaran Barang
    </h2>

    <div class="px-6 py-4 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

        {{-- Menampilkan pesan peringatan jika stok kurang --}}
        @if (session('warning'))
            <div class="px-4 py-3 mb-4 text-sm font-medium text-yellow-900 bg-yellow-200 border border-yellow-300 rounded-lg" role="alert">
                <p>{{ session('warning') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-red-500 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Informasi Tugas -->
        <div class="mb-6 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700">
            <h4 class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-200">Detail Tugas</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Produk:</p>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ optional($task->product)->name }} (Stok Saat Ini: {{ optional($task->product)->stock ?? 0 }})</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Jumlah Diminta:</p>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ $task->quantity }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 dark:text-gray-400">Tujuan/Catatan Awal:</p>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ $task->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Formulir Konfirmasi -->
        <form action="{{ route('staff.stock.outgoing.dispatch', $task) }}" method="POST">
            @csrf

            <!-- Jumlah Aktual Dikeluarkan -->
            <label class="block">
                <span class="text-gray-700 dark:text-gray-400">Jumlah Aktual Dikeluarkan<span class="text-red-500">*</span></span>
                <input
                    type="number"
                    name="quantity_dispatched"
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="Jumlah barang yang benar-benar keluar"
                    value="{{ old('quantity_dispatched', min($task->quantity, optional($task->product)->stock ?? 0)) }}"
                    required
                    max="{{ optional($task->product)->stock ?? 0 }}"
                />
                @error('quantity_dispatched')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ url()->previous(route('staff.dashboard')) }}"
                   class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-200 border border-transparent rounded-lg hover:bg-gray-300 focus:outline-none focus:shadow-outline-gray dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-5 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
                    Konfirmasi Pengeluaran
                </button>
            </div>
        </form>
    </div>
@endsection