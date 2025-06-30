@extends('layouts.dashboard')

@section('title', 'Pengaturan Aplikasi')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <span>Pengaturan</span>
        </div>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Aplikasi</h1>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-800 dark:border-green-600 dark:text-green-200">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-800 dark:border-red-600 dark:text-red-200">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 mr-4 bg-blue-100 rounded-lg dark:bg-blue-800">
                    <i class="text-blue-600 fas fa-cog dark:text-blue-300"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Konfigurasi Aplikasi</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pengaturan umum aplikasi Anda</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                @method('POST') {{-- Atau PUT jika Anda mendefinisikan route sebagai PUT/PATCH --}}

                <div class="space-y-6">
                    <!-- App Name Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Aplikasi</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Aplikasi
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('app_name') border-red-500 @enderror" 
                                       id="app_name" 
                                       name="app_name" 
                                       value="{{ old('app_name', $app_name ?? '') }}" 
                                       placeholder="Masukkan nama aplikasi"
                                       required>
                                @error('app_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Logo Section -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Logo Aplikasi</h3>
                        
                        <div class="space-y-4">
                            <!-- Current Logo Display -->
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    {{-- Container untuk preview logo --}}
                                    <img src="{{ $app_logo ?? 'https://via.placeholder.com/80x80.png?text=No+Logo' }}" 
                                         alt="Logo Aplikasi" 
                                         id="logo-preview" {{-- ID untuk Javascript --}}
                                         class="object-contain w-20 h-20 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" id="logo-status">
                                        @if(isset($app_logo) && $app_logo) Logo Saat Ini @else Belum Ada Logo @endif
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Unggah file baru untuk mengganti logo</p>
                                </div>
                            </div>
                           
                            <!-- File Upload -->
                            <div>
                                <label for="app_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Logo Baru
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="app_logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-box-text">
                                            <i class="mb-3 text-gray-400 fas fa-cloud-upload-alt fa-2x"></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, SVG (MAX. 2MB)</p>
                                        </div>
                                        {{-- Tampilkan nama file yang dipilih --}}
                                        <div id="file-name-display" class="hidden text-sm text-gray-700 dark:text-gray-300"></div>
                                        <input id="app_logo" 
                                               name="app_logo" 
                                               type="file" 
                                               class="hidden @error('app_logo') border-red-500 @enderror" 
                                               accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml">
                                    </label>
                                </div>
                                @error('app_logo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 transition-colors duration-150">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-150">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ... Sisa kode (cards) bisa tetap sama ... -->
    <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- System Info Card -->
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 mr-3 bg-green-100 rounded-lg dark:bg-green-800">
                        <i class="text-green-600 fas fa-server dark:text-green-300"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sistem</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Informasi sistem</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Laravel:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">PHP:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cache Management Card -->
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 mr-3 bg-orange-100 rounded-lg dark:bg-orange-800">
                        <i class="text-orange-600 fas fa-bolt dark:text-orange-300"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cache</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola cache aplikasi</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" 
                            onclick="clearCache()"
                            class="w-full px-4 py-2 text-sm font-medium text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 dark:bg-orange-800 dark:text-orange-200 dark:hover:bg-orange-700 transition-colors duration-150">
                        <i class="fas fa-trash mr-2"></i>Clear Cache
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup Card -->
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 mr-3 bg-purple-100 rounded-lg dark:bg-purple-800">
                        <i class="text-purple-600 fas fa-database dark:text-purple-300"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Backup</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Backup database</p>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" 
                            onclick="createBackup()"
                            class="w-full px-4 py-2 text-sm font-medium text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 dark:bg-purple-800 dark:text-purple-200 dark:hover:bg-purple-700 transition-colors duration-150">
                        <i class="fas fa-download mr-2"></i>Create Backup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('[class*="bg-green-100"], [class*="bg-red-100"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    });

    // File upload preview logic
    const fileInput = document.getElementById('app_logo');
    const logoPreview = document.getElementById('logo-preview');
    const logoStatus = document.getElementById('logo-status');
    const uploadBoxText = document.getElementById('upload-box-text');
    const fileNameDisplay = document.getElementById('file-name-display');
    const defaultLogo = 'https://via.placeholder.com/80x80.png?text=No+Logo';

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    logoPreview.src = event.target.result;
                }
                reader.readAsDataURL(file);
                
                logoStatus.textContent = 'Logo Pratinjau';
                uploadBoxText.classList.add('hidden'); // Sembunyikan teks "Klik untuk upload"
                fileNameDisplay.textContent = file.name; // Tampilkan nama file
                fileNameDisplay.classList.remove('hidden');
            } else {
                // Jika user membatalkan pilihan file
                logoPreview.src = "{{ $app_logo ?? 'https://via.placeholder.com/80x80.png?text=No+Logo' }}";
                logoStatus.textContent = "@if(isset($app_logo) && $app_logo) Logo Saat Ini @else Belum Ada Logo @endif";
                uploadBoxText.classList.remove('hidden');
                fileNameDisplay.classList.add('hidden');
            }
        });
    }
});

// Clear Cache Function (tetap sama)
function clearCache() {
    // ... kode clearCache Anda sudah benar
}

// Create Backup Function (tetap sama)
function createBackup() {
    // ... kode createBackup Anda sudah benar
}
</script>
@endpush