 @extends('layouts.dashboard')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori</a>
            <span>/</span>
            <span>Tambah Kategori</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Kategori Baru</h1>
        <p class="text-gray-600 dark:text-gray-400">Buat kategori produk baru untuk mengorganisir inventory</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    {{-- Nama Kategori --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Masukkan nama kategori"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Nama kategori harus unik dan belum pernah digunakan</p>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Masukkan deskripsi kategori (opsional)"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-1">Jelaskan kategori ini untuk memudahkan identifikasi</p>
                    </div>
                </div>

                {{-- Category Guidelines --}}
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Panduan Kategori:
                    </h3>
                    <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
                        <li>• Gunakan nama yang jelas dan mudah dipahami</li>
                        <li>• Hindari penggunaan karakter khusus yang tidak perlu</li>
                        <li>• Pastikan nama kategori mencerminkan jenis produk yang akan dikelompokkan</li>
                        <li>• Deskripsi membantu tim memahami tujuan kategori ini</li>
                    </ul>
                </div>

                {{-- Preview Section --}}
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-eye mr-2"></i>Preview Kategori:
                    </h3>
                    <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <i class="fas fa-tag text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white" id="preview-name">
                                [Nama kategori akan muncul di sini]
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400" id="preview-description">
                                [Deskripsi kategori akan muncul di sini]
                            </div>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full">
                                0 produk
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                     <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-2"></i>Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live preview for category name and description
        document.getElementById('name').addEventListener('input', function() {
            const previewName = document.getElementById('preview-name');
            if (this.value) {
                previewName.textContent = this.value;
                previewName.classList.remove('text-gray-400');
                previewName.classList.add('text-gray-900', 'dark:text-white');
            } else {
                previewName.textContent = '[Nama kategori akan muncul di sini]';
                previewName.classList.remove('text-gray-900', 'dark:text-white');
                previewName.classList.add('text-gray-400');
            }
        });

        document.getElementById('description').addEventListener('input', function() {
            const previewDesc = document.getElementById('preview-description');
            if (this.value) {
                previewDesc.textContent = this.value;
                previewDesc.classList.remove('text-gray-400');
                previewDesc.classList.add('text-gray-500', 'dark:text-gray-400');
            } else {
                previewDesc.textContent = '[Deskripsi kategori akan muncul di sini]';
                previewDesc.classList.remove('text-gray-500', 'dark:text-gray-400');
                previewDesc.classList.add('text-gray-400');
            }
        });
    </script>
    @endpush
@endsection
