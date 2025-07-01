@extends('layouts.dashboard')

@section('title', 'Konfirmasi Penerimaan Barang')

@section('content')
    <h2 class="mb-4 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Konfirmasi Penerimaan Barang Masuk
    </h2>

    <div class="px-6 py-4 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

        <!-- Informasi Tugas -->
        <div class="mb-6 p-4 border rounded-lg">
            <h4 class="mb-2 text-lg font-semibold">Detail Tugas</h4>
            <p><strong>Produk:</strong> {{ optional($task->product)->name }}</p>
            <p><strong>Jumlah Dipesan:</strong> {{ $task->quantity }}</p>
            <p><strong>Dari Supplier:</strong> {{ optional($task->supplier)->name ?? 'N/A' }}</p>
        </div>

        <!-- Formulir Konfirmasi -->
        <form action="{{ route('staff.stock.incoming.complete', $task) }}" method="POST">
            @csrf
            
            <label class="block mb-4">
                <span class="text-gray-700">Jumlah Aktual Diterima<span class="text-red-500">*</span></span>
                <input type="number" name="quantity_received" value="{{ old('quantity_received', $task->quantity) }}" required class="block w-full mt-1 form-input">
                @error('quantity_received') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block mb-4">
                <span class="text-gray-700">Tanggal Penerimaan<span class="text-red-500">*</span></span>
                 <input type="date" name="received_date" value="{{ old('received_date', now()->format('Y-m-d')) }}" required class="block w-full mt-1 form-input">
                @error('received_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </label>

            <label class="block mb-4">
                <span class="text-gray-700">Catatan Tambahan (Opsional)</span>
                <textarea name="additional_notes" class="block w-full mt-1 form-textarea" rows="3">{{ old('additional_notes') }}</textarea>
            </label>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-5 py-2 font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                    Konfirmasi Penerimaan
                </button>
            </div>
        </form>
    </div>
@endsection