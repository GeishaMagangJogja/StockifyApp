@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')

@section('content')
    <!-- Header Section with Glass Effect -->
    <div class="relative mb-8 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl backdrop-blur-sm"></div>
        <div class="relative p-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center mb-4 space-x-2 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-3 py-1 text-gray-600 transition-all duration-200 rounded-lg hover:text-blue-600 hover:bg-white/50 dark:text-gray-400 dark:hover:bg-gray-800/50">
                    <i class="mr-2 fas fa-home"></i>Dashboard
                </a>
                <i class="text-gray-400 fas fa-chevron-right"></i>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-3 py-1 text-gray-600 transition-all duration-200 rounded-lg hover:text-blue-600 hover:bg-white/50 dark:text-gray-400 dark:hover:bg-gray-800/50">
                    <i class="mr-2 fas fa-users"></i>Pengguna
                </a>
                <i class="text-gray-400 fas fa-chevron-right"></i>
                <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                    <i class="mr-2 fas fa-plus"></i>Tambah
                </span>
            </nav>

            <!-- Title Section -->
            <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-user-plus dark:text-blue-400"></i>
                        Tambah Pengguna Baru
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Buat akun pengguna baru untuk sistem gudang
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="text-gray-400 fas fa-user"></i>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="text-gray-400 fas fa-envelope"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror"
                                   placeholder="Masukkan alamat email">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="text-gray-400 fas fa-user-tag"></i>
                            </div>
                            <select id="role" name="role" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('role') border-red-500 @enderror">
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="text-gray-400 fas fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror"
                                   placeholder="Buat password">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimal 8 karakter</p>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="text-gray-400 fas fa-lock"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="Konfirmasi password">
                        </div>
                    </div>

                    <!-- Role Description -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl">
                            <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="mr-3 text-xl text-blue-400 fas fa-lightbulb"></i>
                                <span>Deskripsi Role</span>
                            </h3>
                            <ul class="space-y-2 text-gray-700 list-disc list-inside dark:text-slate-300">
                                <li>
                                    <strong class="font-medium text-gray-800 dark:text-slate-100">Admin:</strong>
                                    Akses penuh ke semua fitur sistem.
                                </li>
                                <li>
                                    <strong class="font-medium text-gray-800 dark:text-slate-100">Manajer Gudang:</strong>
                                    Mengelola stok, transaksi, dan laporan.
                                </li>
                                <li>
                                    <strong class="font-medium text-gray-800 dark:text-slate-100">Staff Gudang:</strong>
                                    Melakukan operasi stok dasar dan tugas yang diberikan.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end pt-6 mt-8 space-x-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-6 py-3 font-medium text-gray-700 transition-all duration-200 bg-gray-200 rounded-xl hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        <i class="mr-2 fas fa-times"></i>Batal
                    </a>
                    <button type="submit"
                            class="relative inline-flex items-center px-6 py-3 text-white transition-all duration-300 shadow-lg group bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 hover:shadow-xl">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                        <i class="mr-2 fas fa-save"></i>
                        <span class="font-medium">Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
