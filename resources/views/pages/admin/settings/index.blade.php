@extends('layouts.dashboard')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="mb-8">
    <!-- Breadcrumb with enhanced styling -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-sm">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center text-gray-500 transition-colors hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                    <i class="w-4 h-4 mr-2 fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="w-4 h-4 text-gray-400 fas fa-chevron-right"></i>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Pengaturan</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with gradient background -->
    <div class="relative p-6 overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-16 h-16 mr-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                    <i class="text-2xl text-white fas fa-cog"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Pengaturan Aplikasi</h1>
                    <p class="text-blue-100">Kelola konfigurasi dan tampilan aplikasi Anda</p>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white opacity-5 rounded-full"></div>
    </div>
</div>

<!-- Enhanced Alert Messages -->
@if(session('success'))
    <div class="relative p-4 mb-6 overflow-hidden text-green-800 border border-green-200 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 dark:border-green-700 dark:text-green-200">
        <div class="absolute inset-0 bg-green-500 opacity-5"></div>
        <div class="relative flex items-center">
            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-green-100 rounded-lg dark:bg-green-800">
                <i class="text-green-600 fas fa-check-circle dark:text-green-300"></i>
            </div>
            <div>
                <p class="font-medium">Berhasil!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="relative p-4 mb-6 overflow-hidden text-red-800 border border-red-200 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 dark:border-red-700 dark:text-red-200">
        <div class="absolute inset-0 bg-red-500 opacity-5"></div>
        <div class="relative flex items-center">
            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-red-100 rounded-lg dark:bg-red-800">
                <i class="text-red-600 fas fa-exclamation-circle dark:text-red-300"></i>
            </div>
            <div>
                <p class="font-medium">Terjadi Kesalahan!</p>
                <p class="text-sm opacity-90">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Main Content Card with enhanced styling -->
<div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800 ring-1 ring-gray-200 dark:ring-gray-700">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- App Info Section -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-start mb-6">
                <div class="flex items-center justify-center w-14 h-14 mr-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i class="text-xl text-white fas fa-info-circle"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Informasi Aplikasi</h2>
                    <p class="text-gray-600 dark:text-gray-400">Atur nama dan deskripsi aplikasi Anda</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-1">
                <div class="space-y-2">
                    <label for="app_name" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="w-4 h-4 mr-2 text-blue-500 fas fa-tag"></i>
                        Nama Aplikasi
                    </label>
                    <input type="text"
                           class="w-full px-4 py-3 transition-all duration-200 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-400 @error('app_name') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror"
                           id="app_name"
                           name="app_name"
                           value="{{ old('app_name', $app_name ?? '') }}"
                           placeholder="Masukkan nama aplikasi yang menarik"
                           required>
                    @error('app_name')
                        <p class="flex items-center mt-2 text-sm text-red-600 dark:text-red-400">
                            <i class="w-4 h-4 mr-1 fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="app_description" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="w-4 h-4 mr-2 text-blue-500 fas fa-align-left"></i>
                        Deskripsi Aplikasi
                    </label>
                    <textarea
                        class="w-full px-4 py-3 transition-all duration-200 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-blue-400 @error('app_description') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror"
                        id="app_description"
                        name="app_description"
                        rows="4"
                        placeholder="Jelaskan tujuan dan fungsi aplikasi Anda..."
                        required>{{ old('app_description', $app_description ?? '') }}</textarea>
                    @error('app_description')
                        <p class="flex items-center mt-2 text-sm text-red-600 dark:text-red-400">
                            <i class="w-4 h-4 mr-1 fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Logo Section with enhanced design -->
        <div class="p-8 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-start mb-6">
                <div class="flex items-center justify-center w-14 h-14 mr-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                    <i class="text-xl text-white fas fa-image"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Logo Aplikasi</h2>
                    <p class="text-gray-600 dark:text-gray-400">Upload logo untuk identitas visual aplikasi</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Current Logo Display -->
                <div class="flex items-center p-6 space-x-6 border-2 border-gray-100 rounded-xl bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700">
                    <div class="relative group">
                        <img src="{{ $app_logo ?? 'https://via.placeholder.com/100x100.png?text=No+Logo' }}"
                             alt="Logo Aplikasi"
                             id="logo-preview"
                             class="object-contain w-24 h-24 transition-all duration-300 bg-white border-2 border-gray-200 rounded-xl shadow-sm group-hover:shadow-md dark:bg-gray-700 dark:border-gray-600">
                        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50 rounded-xl opacity-0 group-hover:opacity-100">
                            <i class="text-white fas fa-search-plus"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="logo-status">
                            @if(isset($app_logo) && $app_logo) 
                                <i class="mr-2 text-green-500 fas fa-check-circle"></i>Logo Aktif
                            @else 
                                <i class="mr-2 text-orange-500 fas fa-exclamation-triangle"></i>Belum Ada Logo
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Upload file baru untuk mengganti logo yang ada</p>
                        <div class="flex mt-2 space-x-2 text-xs text-gray-500">
                            <span class="px-2 py-1 bg-gray-200 rounded-full dark:bg-gray-700">Rekomendasi: 512x512px</span>
                            <span class="px-2 py-1 bg-gray-200 rounded-full dark:bg-gray-700">Format: PNG, JPG, SVG</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div>
                    <label for="app_logo" class="flex items-center mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="w-4 h-4 mr-2 text-purple-500 fas fa-cloud-upload-alt"></i>
                        Upload Logo Baru
                    </label>
                    <div class="relative">
                        <label for="app_logo" class="flex flex-col items-center justify-center w-full h-40 transition-all duration-300 border-2 border-gray-300 border-dashed cursor-pointer rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:border-blue-500 group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-all duration-300 group-hover:scale-105" id="upload-box-text">
                                <div class="mb-4">
                                    <i class="text-4xl text-gray-400 transition-colors duration-300 fas fa-cloud-upload-alt group-hover:text-blue-500"></i>
                                </div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <span class="font-bold text-blue-600 dark:text-blue-400">Klik untuk upload</span> atau seret file ke sini
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, SVG (Maksimal 2MB)</p>
                            </div>
                            <div id="file-name-display" class="items-center hidden p-4 space-x-3 text-sm text-gray-700 bg-white border rounded-lg shadow-sm dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                <i class="text-green-500 fas fa-file-image"></i>
                                <span id="file-name-text"></span>
                                <i class="text-gray-400 fas fa-times cursor-pointer hover:text-red-500" onclick="clearFileInput()"></i>
                            </div>
                            <input id="app_logo"
                                   name="app_logo"
                                   type="file"
                                   class="hidden @error('app_logo') border-red-500 @enderror"
                                   accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml">
                        </label>
                    </div>
                    @error('app_logo')
                        <p class="flex items-center mt-2 text-sm text-red-600 dark:text-red-400">
                            <i class="w-4 h-4 mr-1 fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons with enhanced styling -->
        <div class="px-8 py-6 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle"></i>
                    <span>Perubahan akan tersimpan secara otomatis setelah submit</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-6 py-3 text-gray-700 transition-all duration-200 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-500 group">
                        <i class="mr-2 transition-transform duration-200 fas fa-times group-hover:scale-110"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center px-8 py-3 text-white transition-all duration-200 shadow-lg bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/30 group">
                        <i class="mr-2 transition-transform duration-200 fas fa-save group-hover:scale-110"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced auto-hide alerts with smooth animation
    const alerts = document.querySelectorAll('[class*="bg-green-"], [class*="bg-red-"]');
    alerts.forEach(alert => {
        // Add close button to alerts
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.className = 'absolute top-3 right-3 text-current opacity-60 hover:opacity-100 transition-opacity';
        closeBtn.onclick = () => hideAlert(alert);
        alert.style.position = 'relative';
        alert.appendChild(closeBtn);

        // Auto hide after 7 seconds
        setTimeout(() => hideAlert(alert), 7000);
    });

    function hideAlert(alert) {
        if (alert) {
            alert.style.transform = 'translateX(100%)';
            alert.style.transition = 'all 0.5s ease-in-out';
            setTimeout(() => alert.remove(), 500);
        }
    }

    // Enhanced file upload preview logic
    const fileInput = document.getElementById('app_logo');
    const logoPreview = document.getElementById('logo-preview');
    const logoStatus = document.getElementById('logo-status');
    const uploadBoxText = document.getElementById('upload-box-text');
    const fileNameDisplay = document.getElementById('file-name-display');
    const fileNameText = document.getElementById('file-name-text');

    if (fileInput) {
        // Drag and drop functionality
        const uploadArea = fileInput.parentElement;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            uploadArea.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        }

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleFileSelect(file);
            } else {
                resetFileDisplay();
            }
        });

        function handleFileSelect(file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar! Maksimal 2MB.');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                logoPreview.src = event.target.result;
                logoPreview.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    logoPreview.style.transform = 'scale(1)';
                    logoPreview.style.transition = 'transform 0.3s ease';
                }, 100);
            }
            reader.readAsDataURL(file);

            logoStatus.innerHTML = '<i class="mr-2 text-blue-500 fas fa-image"></i>Logo Pratinjau';
            uploadBoxText.classList.add('hidden');
            fileNameDisplay.classList.remove('hidden');
            fileNameDisplay.classList.add('flex');
            fileNameText.textContent = file.name;
        }

        function resetFileDisplay() {
            logoPreview.src = "{{ $app_logo ?? 'https://via.placeholder.com/100x100.png?text=No+Logo' }}";
            logoStatus.innerHTML = "@if(isset($app_logo) && $app_logo) <i class='mr-2 text-green-500 fas fa-check-circle'></i>Logo Aktif @else <i class='mr-2 text-orange-500 fas fa-exclamation-triangle'></i>Belum Ada Logo @endif";
            uploadBoxText.classList.remove('hidden');
            fileNameDisplay.classList.add('hidden');
            fileNameDisplay.classList.remove('flex');
        }
    }

    // Add loading state to submit button
    const submitBtn = document.querySelector('button[type="submit"]');
    const form = document.querySelector('form');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Menyimpan...';
            submitBtn.classList.add('opacity-75');
        });
    }
});

// Clear file input function
function clearFileInput() {
    const fileInput = document.getElementById('app_logo');
    fileInput.value = '';
    fileInput.dispatchEvent(new Event('change'));
}
</script>
@endpush