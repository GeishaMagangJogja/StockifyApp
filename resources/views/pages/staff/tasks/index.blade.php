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
        <div class="p-4 mb-6 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="p-4 mb-6 text-sm text-yellow-800 bg-yellow-100 rounded-lg dark:bg-yellow-900 dark:text-yellow-300">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Layout Dua Kolom untuk Tugas --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

        <!-- Kolom Tugas Barang Masuk -->
        <div class="overflow-hidden bg-white shadow-lg dark:bg-gray-800 rounded-2xl">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h2 class="flex items-center text-xl font-bold text-white">
                    <i class="mr-3 fas fa-arrow-down"></i>
                    Tugas Barang Masuk (Pending)
                </h2>
                <p class="mt-1 text-green-100">{{ count($incomingTasks) }} tugas menunggu konfirmasi</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($incomingTasks as $task)
                    <div class="flex items-center justify-between p-4 border-l-4 border-green-400 rounded-xl bg-gray-50 dark:bg-slate-700">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Qty: <span class="font-medium text-green-600">{{ $task->quantity }}</span> |
                                Dari: {{ optional($task->supplier)->name ?? 'N/A' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Tanggal: {{ $task->date ? $task->date->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="flex flex-col ml-4 space-y-2">
                            {{-- Tombol Approve/Proses --}}
                            <a href="{{ route('staff.tasks.incoming.confirm', $task->id) }}"
                               title="Proses Tugas"
                               class="px-4 py-2 text-xs font-medium text-center text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="mr-1 fas fa-check"></i> Proses
                            </a>
                            {{-- Tombol Reject --}}
                            <button type="button"
                                    onclick="showRejectModal('incoming', {{ $task->id }}, '{{ $task->product->name ?? 'N/A' }}')"
                                    title="Tolak Tugas"
                                    class="px-4 py-2 text-xs font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                                <i class="mr-1 fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="mb-3 text-4xl text-green-400 fas fa-check-circle"></i>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak Ada Tugas</p>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">Semua tugas barang masuk sudah selesai.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Tugas Barang Keluar -->
        <div class="overflow-hidden bg-white shadow-lg dark:bg-gray-800 rounded-2xl">
            <div class="p-6 bg-gradient-to-r from-orange-500 to-red-600">
                <h2 class="flex items-center text-xl font-bold text-white">
                    <i class="mr-3 fas fa-arrow-up"></i>
                    Tugas Barang Keluar (Pending)
                </h2>
                <p class="mt-1 text-orange-100">{{ count($outgoingTasks) }} tugas perlu disiapkan</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($outgoingTasks as $task)
                    <div class="flex items-center justify-between p-4 border-l-4 border-orange-400 rounded-xl bg-gray-50 dark:bg-slate-700">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Qty: <span class="font-medium text-orange-600">{{ $task->quantity }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Stok Tersedia: {{ optional($task->product)->stock ?? 0 }}
                            </p>
                        </div>
                        <div class="flex flex-col ml-4 space-y-2">
                            {{-- Tombol Approve/Siapkan --}}
                            <a href="{{ route('staff.tasks.outgoing.prepare', $task->id) }}"
                               title="Siapkan Tugas"
                               class="px-4 py-2 text-xs font-medium text-center text-white transition-colors bg-orange-600 rounded-lg hover:bg-orange-700">
                                <i class="mr-1 fas fa-cogs"></i> Siapkan
                            </a>
                            {{-- Tombol Reject --}}
                            <button type="button"
                                    onclick="showRejectModal('outgoing', {{ $task->id }}, '{{ $task->product->name ?? 'N/A' }}')"
                                    title="Tolak Tugas"
                                    class="px-4 py-2 text-xs font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                                <i class="mr-1 fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="mb-3 text-4xl text-green-400 fas fa-check-circle"></i>
                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Tidak Ada Tugas</p>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">Semua tugas barang keluar sudah selesai.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Modal Reject --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div class="relative p-5 mx-auto bg-white border rounded-md shadow-lg top-20 w-96 dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900">
                    <i class="text-xl text-red-600 fas fa-exclamation-triangle dark:text-red-400"></i>
                </div>
                <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Tolak Tugas</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Yakin ingin menolak tugas untuk produk <span id="productName" class="font-semibold"></span>?
                </p>
                <form id="rejectForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea id="rejection_reason"
                                  name="rejection_reason"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button type="button"
                                onclick="hideRejectModal()"
                                class="px-4 py-2 text-gray-700 transition-colors bg-gray-300 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-white transition-colors bg-red-600 rounded-md hover:bg-red-700">
                            Tolak Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showRejectModal(type, taskId, productName) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            const productNameSpan = document.getElementById('productName');

            // Set product name
            productNameSpan.textContent = productName;

            // Set form action
            const baseUrl = type === 'incoming' ?
                "{{ route('staff.tasks.incoming.reject', ':id') }}" :
                "{{ route('staff.tasks.outgoing.reject', ':id') }}";
            form.action = baseUrl.replace(':id', taskId);

            // Show modal
            modal.classList.remove('hidden');
        }

        function hideRejectModal() {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            const textarea = document.getElementById('rejection_reason');

            // Reset form
            form.reset();
            textarea.value = '';

            // Hide modal
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
    @endpush
@endsection
