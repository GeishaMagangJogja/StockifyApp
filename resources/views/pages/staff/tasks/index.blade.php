@extends('layouts.dashboard')

@section('title', 'Pusat Pengerjaan Tugas')

@section('content')
    {{-- Header Section dengan animasi --}}
    <div class="mb-8 animate-fade-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent dark:from-white dark:to-gray-300">
                    Pusat Pengerjaan Tugas
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                    Kelola semua tugas dengan efisien dan terorganisir
                </p>
            </div>
            {{-- Stats Summary --}}
            <div class="hidden md:flex space-x-4">
                <div class="bg-green-100 dark:bg-green-900 px-4 py-2 rounded-xl">
                    <span class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ count($incomingTasks) }} Masuk
                    </span>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 px-4 py-2 rounded-xl">
                    <span class="text-sm font-medium text-orange-800 dark:text-orange-300">
                        {{ count($outgoingTasks) }} Keluar
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Notifications --}}
    @if(session('success'))
        <div class="animate-slide-down p-4 mb-6 text-sm text-green-800 bg-green-100 border border-green-200 rounded-xl dark:bg-green-900 dark:text-green-300 dark:border-green-700 shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="animate-slide-down p-4 mb-6 text-sm text-red-800 bg-red-100 border border-red-200 rounded-xl dark:bg-red-900 dark:text-red-300 dark:border-red-700 shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-slide-down p-4 mb-6 text-sm text-yellow-800 bg-yellow-100 border border-yellow-200 rounded-xl dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700 shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
            </div>
        </div>
    @endif

    {{-- Enhanced Two-Column Layout --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

        <!-- Enhanced Incoming Tasks Column -->
        <div class="group overflow-hidden bg-white shadow-2xl dark:bg-gray-800 rounded-3xl hover:shadow-3xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="relative p-6 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 overflow-hidden">
                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-8 -translate-x-8"></div>
                
                <div class="relative z-10">
                    <h2 class="flex items-center text-xl font-bold text-white mb-2">
                        <div class="p-2 bg-white/20 rounded-xl mr-3 backdrop-blur-sm">
                            <i class="fas fa-arrow-down text-white"></i>
                        </div>
                        Tugas Barang Masuk
                    </h2>
                    <p class="text-green-100 font-medium">
                        {{ count($incomingTasks) }} tugas menunggu konfirmasi
                    </p>
                </div>
            </div>
            
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                @forelse ($incomingTasks as $index => $task)
                    <div class="task-card group/card bg-gradient-to-r from-gray-50 to-green-50 dark:from-slate-700 dark:to-slate-600 p-4 rounded-2xl border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                         style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                    <p class="font-bold text-gray-800 dark:text-white text-lg">
                                        {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-cubes mr-1 text-green-600"></i>
                                        <span class="font-semibold">{{ $task->quantity }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-truck mr-1 text-blue-600"></i>
                                        <span>{{ optional($task->supplier)->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $task->date ? $task->date->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="flex flex-col ml-4 space-y-2">
                                <a href="{{ route('staff.tasks.incoming.confirm', $task->id) }}"
                                   title="Proses Tugas"
                                   class="action-btn bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-check mr-1"></i> Proses
                                </a>
                                <button type="button"
                                        onclick="showRejectModal('incoming', {{ $task->id }}, '{{ $task->product->name ?? 'N/A' }}')"
                                        title="Tolak Tugas"
                                        class="action-btn bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">Semua Tugas Selesai!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada tugas barang masuk yang menunggu.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Enhanced Outgoing Tasks Column -->
        <div class="group overflow-hidden bg-white shadow-2xl dark:bg-gray-800 rounded-3xl hover:shadow-3xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="relative p-6 bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 overflow-hidden">
                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-8 -translate-x-8"></div>
                
                <div class="relative z-10">
                    <h2 class="flex items-center text-xl font-bold text-white mb-2">
                        <div class="p-2 bg-white/20 rounded-xl mr-3 backdrop-blur-sm">
                            <i class="fas fa-arrow-up text-white"></i>
                        </div>
                        Tugas Barang Keluar
                    </h2>
                    <p class="text-orange-100 font-medium">
                        {{ count($outgoingTasks) }} tugas perlu disiapkan
                    </p>
                </div>
            </div>
            
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                @forelse ($outgoingTasks as $index => $task)
                    <div class="task-card group/card bg-gradient-to-r from-gray-50 to-orange-50 dark:from-slate-700 dark:to-slate-600 p-4 rounded-2xl border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
                         style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2 animate-pulse"></div>
                                    <p class="font-bold text-gray-800 dark:text-white text-lg">
                                        {{ optional($task->product)->name ?? 'Produk tidak ditemukan' }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-cubes mr-1 text-orange-600"></i>
                                        <span class="font-semibold">{{ $task->quantity }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-warehouse mr-1 text-blue-600"></i>
                                        <span>Stok: {{ optional($task->product)->stock ?? 0 }}</span>
                                    </div>
                                </div>
                                {{-- Stock availability indicator --}}
                                @php
                                    $stockAvailable = optional($task->product)->stock ?? 0;
                                    $isLowStock = $stockAvailable < $task->quantity;
                                @endphp
                                <div class="flex items-center mt-2">
                                    @if($isLowStock)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Stok Kurang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Stok Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col ml-4 space-y-2">
                                <a href="{{ route('staff.tasks.outgoing.prepare', $task->id) }}"
                                   title="Siapkan Tugas"
                                   class="action-btn bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-cogs mr-1"></i> Siapkan
                                </a>
                                <button type="button"
                                        onclick="showRejectModal('outgoing', {{ $task->id }}, '{{ $task->product->name ?? 'N/A' }}')"
                                        title="Tolak Tugas"
                                        class="action-btn bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">Semua Tugas Selesai!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada tugas barang keluar yang menunggu.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Enhanced Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <div class="p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tolak Tugas</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Yakin ingin menolak tugas untuk produk <span id="productName" class="font-bold text-gray-900 dark:text-white"></span>?
                        </p>
                        
                        <form id="rejectForm" method="POST" class="space-y-4">
                            @csrf
                            <div class="text-left">
                                <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Alasan Penolakan (Opsional)
                                </label>
                                <textarea id="rejection_reason"
                                          name="rejection_reason"
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                          placeholder="Tuliskan alasan penolakan di sini..."></textarea>
                            </div>
                            <div class="flex space-x-3 pt-4">
                                <button type="button"
                                        onclick="hideRejectModal()"
                                        class="flex-1 px-4 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-semibold">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="flex-1 px-4 py-3 text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                                    Tolak Tugas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-down {
            animation: slideDown 0.5s ease-out;
        }
        
        .task-card {
            animation: slideUp 0.5s ease-out both;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .action-btn {
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        .empty-state {
            animation: fadeIn 1s ease-out;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function showRejectModal(type, taskId, productName) {
            const modal = document.getElementById('rejectModal');
            const modalContent = document.getElementById('modalContent');
            const form = document.getElementById('rejectForm');
            const productNameSpan = document.getElementById('productName');

            // Set product name
            productNameSpan.textContent = productName;

            // Set form action
            const baseUrl = type === 'incoming' ?
                "{{ route('staff.tasks.incoming.reject', ':id') }}" :
                "{{ route('staff.tasks.outgoing.reject', ':id') }}";
            form.action = baseUrl.replace(':id', taskId);

            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideRejectModal() {
            const modal = document.getElementById('rejectModal');
            const modalContent = document.getElementById('modalContent');
            const form = document.getElementById('rejectForm');
            const textarea = document.getElementById('rejection_reason');

            // Hide modal with animation
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                form.reset();
                textarea.value = '';
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });

        // Add keyboard support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('rejectModal').classList.contains('hidden')) {
                hideRejectModal();
            }
        });

        // Add loading state to buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.tagName === 'A') {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
                    this.classList.add('opacity-75', 'cursor-not-allowed');
                }
            });
        });
    </script>
    @endpush
@endsection