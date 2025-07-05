@extends('layouts.dashboard')

@section('title', 'Konfirmasi Penerimaan Barang')

@section('content')
<div class="container px-4 py-6 mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="mb-2 text-3xl font-bold text-gray-800 dark:text-gray-100">
            Konfirmasi Penerimaan Barang
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Konfirmasi penerimaan barang masuk dari supplier
        </p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Detail Tugas -->
        <div class="lg:col-span-1">
            <div class="p-6 bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-xl dark:border-gray-700">
                <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Detail Tugas
                </h3>

                <div class="space-y-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Produk</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $task->product ? $task->product->name : 'Produk tidak ditemukan' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Dipesan</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($task->quantity) }} unit
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $task->supplier ? $task->supplier->name : 'Supplier tidak ditemukan' }}
                        </p>
                    </div>

                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Permintaan</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $task->created_at ? $task->created_at->format('d M Y H:i') : 'Tidak tersedia' }}
                        </p>
                    </div>

                    @if($task->notes)
                    <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-700">
                        <label class="block mb-1 text-sm font-medium text-yellow-700 dark:text-yellow-300">Catatan</label>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $task->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Konfirmasi -->
        <div class="lg:col-span-2">
            <div class="p-6 bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-xl dark:border-gray-700">
                <h3 class="flex items-center mb-6 text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Form Konfirmasi Penerimaan
                </h3>

                <form action="{{ route('staff.tasks.incoming.approve', $task) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Jumlah Aktual Diterima -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jumlah Aktual Diterima
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                name="quantity_received"
                                value="{{ old('quantity_received', $task->quantity) }}"
                                required
                                min="1"
                                max="999999"
                                class="w-full px-4 py-3 transition-colors border border-gray-300 rounded-lg dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="Masukkan jumlah barang yang diterima"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">unit</span>
                            </div>
                        </div>
                        @error('quantity_received')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tanggal Penerimaan -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Penerimaan
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="received_date"
                            value="{{ old('received_date', now()->format('Y-m-d')) }}"
                            required
                            max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-3 transition-colors border border-gray-300 rounded-lg dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                        >
                        @error('received_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Catatan Tambahan
                            <span class="text-xs text-gray-500">(Opsional)</span>
                        </label>
                        <textarea
                            name="additional_notes"
                            rows="4"
                            class="w-full px-4 py-3 transition-colors border border-gray-300 rounded-lg resize-none dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="Tambahkan catatan jika diperlukan, misalnya kondisi barang, kekurangan, dll."
                        >{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-4 pt-6 border-t border-gray-200 sm:flex-row dark:border-gray-700">
                        <button
                            type="submit"
                            class="flex-1 px-6 py-3 font-medium text-white transition-all duration-200 transform rounded-lg shadow-lg sm:flex-none bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Konfirmasi Penerimaan
                        </button>

                        <a
                            href="{{ route('staff.tasks.index') }}"
                            class="flex-1 px-6 py-3 font-medium text-center text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg sm:flex-none hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300"
                        >
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('input[name="quantity_received"]');
    const dateInput = document.querySelector('input[name="received_date"]');

    // Prevent negative values
    quantityInput.addEventListener('input', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });

    // Prevent future dates
    dateInput.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (this.value > today) {
            this.value = today;
            alert('Tanggal penerimaan tidak boleh lebih dari hari ini');
        }
    });
});
</script>
@endpush
@endsection
