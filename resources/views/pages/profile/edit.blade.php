@extends('layouts.dashboard')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Pengaturan Profil</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Perbarui informasi profil dan password Anda.</p>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                 class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Form --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Kartu Informasi Profil --}}
                <form action="{{ match(Auth::user()->role) {
                                'Admin' => route('admin.profile.update'),
                                'Manajer Gudang' => route('manajergudang.profile.update'),
                                'Staff Gudang' => route('staff.profile.update'),
                               } }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                        <div class="p-6 border-b dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Profil</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi ini akan ditampilkan secara publik.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Foto Profil --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil</label>
                                <div class="mt-1 flex items-center space-x-4">
                                    <img id="photo-preview" class="h-20 w-20 rounded-full object-cover" 
                                         src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=3b82f6&color=fff' }}" 
                                         alt="{{ $user->name }}">
                                    <label for="photo" class="cursor-pointer px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600">
                                        <span>Ganti Foto</span>
                                        <input type="file" name="photo" id="photo" class="sr-only" onchange="document.getElementById('photo-preview').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                                @error('photo')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            {{-- Nama & Email --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu Ubah Password --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow mt-8">
                        <div class="p-6 border-b dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Perbarui Password</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pastikan Anda menggunakan password yang panjang dan acak.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" autocomplete="current-password" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                                @error('current_password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                                <input type="password" id="new_password" name="new_password" autocomplete="new-password" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                                @error('new_password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                             <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Kolom Kanan: Info Tambahan --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6 sticky top-24">
                     <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b dark:border-slate-700 pb-4">Keamanan Akun</h3>
                     <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-4">
                        <p><i class="fas fa-check-circle mr-2 text-green-500"></i> Untuk mengubah password, Anda wajib mengisi field "Password Saat Ini".</p>
                        <p><i class="fas fa-info-circle mr-2 text-blue-500"></i> Jika Anda tidak ingin mengubah password, biarkan ketiga field password kosong.</p>
                        <p><i class="fas fa-shield-alt mr-2 text-yellow-500"></i> Segera logout dari semua perangkat jika Anda merasa akun Anda telah diakses oleh orang lain.</p>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection