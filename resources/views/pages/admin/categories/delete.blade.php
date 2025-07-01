@extends('layouts.dashboard')

@section('title', 'Konfirmasi Hapus Kategori')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span>/</span>
            <span>Konfirmasi Hapus</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus Kategori</h1>
                <p class="text-gray-600 dark:text-gray-400">Anda akan menghapus kategori ini secara permanen</p>
            </div>
        </div>
    </div>

    <!-- Success Notification -->
    @if(session('success'))
    <div id="successNotification" class="mb-6 transition-all duration-300 ease-in-out">
        <div class="flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-5 h-5 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <i class="fas fa-check"></i>
            </div>
            <div class="ml-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" onclick="dismissNotification('successNotification')">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Error Notification -->
    @if(session('error'))
    <div id="errorNotification" class="mb-6 transition-all duration-300 ease-in-out">
        <div class="flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-5 h-5 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="ml-3 text-sm font-medium">
                {{ session('error') }}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" onclick="dismissNotification('errorNotification')">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 mr-6 bg-gray-100 rounded-lg dark:bg-gray-700">
                    <i class="text-2xl text-gray-500 fas fa-tags dark:text-gray-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h2>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <p><span class="font-medium">Jumlah Produk:</span> {{ $category->products_count }}</p>
                        <p><span class="font-medium">Dibuat:</span> {{ $category->created_at->format('d M Y H:i') }}</p>
                        <p><span class="font-medium">Diperbarui:</span> {{ $category->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Warning Box -->
            <div class="p-4 mb-6 text-yellow-800 border-l-4 border-yellow-400 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:border-yellow-600 dark:text-yellow-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <i class="text-yellow-500 fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold">Peringatan!</h3>
                        <div class="mt-1 text-sm">
                            <ul class="pl-5 space-y-1 list-disc">
                                @if($category->products_count > 0)
                                <li>Kategori ini memiliki {{ $category->products_count }} produk terkait</li>
                                <li>Semua produk dalam kategori ini akan dikategorikan sebagai "Tidak Berkategori"</li>
                                @endif
                                <li>Tindakan ini tidak dapat dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Form -->
            <form id="deleteForm" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="button"
                            onclick="confirmDelete()"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700">
                        <i class="mr-2 fas fa-trash-alt"></i> Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Delete confirmation with custom modal
        function confirmDelete() {
            // Create modal element
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity';
            modal.innerHTML = `
                <div class="w-full max-w-md overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 pt-0.5 text-red-500">
                                <i class="text-xl fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Konfirmasi Penghapusan</h3>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <p>Anda akan menghapus kategori <span class="font-semibold">"{{ $category->name }}"</span> secara permanen.</p>
                                    @if($category->products_count > 0)
                                    <p class="mt-2">Kategori ini memiliki {{ $category->products_count }} produk yang akan menjadi tidak berkategori.</p>
                                    @endif
                                    <p class="mt-2 text-red-500 dark:text-red-400">Tindakan ini tidak dapat dibatalkan!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end px-6 py-4 space-x-3 bg-gray-50 dark:bg-gray-700">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                            Batal
                        </button>
                        <button type="button" onclick="submitDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-700">
                            <i class="mr-2 fas fa-trash-alt"></i> Hapus Permanen
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.querySelector('.fixed.inset-0.z-50');
            if (modal) {
                modal.remove();
                document.body.style.overflow = '';
            }
        }

        function submitDelete() {
            const form = document.getElementById('deleteForm');
            const button = form.querySelector('button[type="button"]');

            // Change button state
            button.disabled = true;
            button.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i> Menghapus...';
            button.classList.remove('hover:bg-red-700');
            button.classList.add('opacity-75');

            // Close modal and submit form
            closeModal();
            form.submit();
        }

        // Dismiss notification
        function dismissNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }

        // Auto-dismiss notifications after 5 seconds
        setTimeout(() => {
            dismissNotification('successNotification');
            dismissNotification('errorNotification');
        }, 5000);
    </script>
@endsection
