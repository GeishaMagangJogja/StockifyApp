@extends('layouts.dashboard')

@section('title', 'Detail Pengguna')

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
                    <i class="mr-2 fas fa-user"></i>Detail
                </span>
            </nav>

            <!-- Title Section -->
            <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-user-circle dark:text-blue-400"></i>
                        Detail Pengguna
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Informasi lengkap pengguna {{ $user->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="relative inline-flex items-center px-6 py-3 text-white transition-all duration-300 shadow-lg group bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 hover:shadow-xl">
                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                        <i class="mr-2 fas fa-edit"></i>
                        <span class="font-medium">Edit Pengguna</span>
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="relative inline-flex items-center px-6 py-3 text-white transition-all duration-300 shadow-lg group bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 hover:shadow-xl">
                            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                            <i class="mr-2 fas fa-trash"></i>
                            <span class="font-medium">Hapus Pengguna</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Information -->
        <div class="space-y-6 lg:col-span-2">
            <!-- User Information Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="flex items-center mb-6 text-xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-user dark:text-blue-400"></i>
                        <span>Informasi Pengguna</span>
                    </h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                Nama Lengkap
                            </label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                Email
                            </label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                Role
                            </label>
                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full
                                @if($user->role === 'Admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @elseif($user->role === 'Manajer Gudang') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @endif">
                                <i class="mr-1 fas
                                    @if($user->role === 'Admin') fa-user-shield
                                    @elseif($user->role === 'Manajer Gudang') fa-user-tie
                                    @else fa-user
                                    @endif"></i>
                                {{ $user->role }}
                            </span>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                User ID
                            </label>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">#{{ $user->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h2 class="flex items-center mb-6 text-xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-shield-alt dark:text-blue-400"></i>
                        <span>Hak Akses Role</span>
                    </h2>

                    @if($user->role === 'Admin')
                        <div class="p-6 border-l-4 border-purple-500 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/30 rounded-xl">
                            <h3 class="flex items-center mb-4 font-semibold text-purple-800 dark:text-purple-300">
                                <i class="mr-3 fas fa-user-shield"></i>
                                <span>Administrator</span>
                            </h3>
                            <ul class="space-y-2 text-sm text-purple-700 dark:text-purple-400">
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-purple-500 fas fa-check-circle"></i>
                                    <span>Akses penuh ke semua fitur sistem</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-purple-500 fas fa-check-circle"></i>
                                    <span>Mengelola pengguna dan hak akses</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-purple-500 fas fa-check-circle"></i>
                                    <span>Mengelola semua data master</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-purple-500 fas fa-check-circle"></i>
                                    <span>Melihat semua laporan dan analitik</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-purple-500 fas fa-check-circle"></i>
                                    <span>Mengatur konfigurasi sistem</span>
                                </li>
                            </ul>
                        </div>
                    @elseif($user->role === 'Manajer Gudang')
                        <div class="p-6 border-l-4 border-blue-500 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/30 rounded-xl">
                            <h3 class="flex items-center mb-4 font-semibold text-blue-800 dark:text-blue-300">
                                <i class="mr-3 fas fa-user-tie"></i>
                                <span>Manajer Gudang</span>
                            </h3>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-blue-500 fas fa-check-circle"></i>
                                    <span>Mengelola stok dan transaksi gudang</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-blue-500 fas fa-check-circle"></i>
                                    <span>Melihat dan membuat laporan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-blue-500 fas fa-check-circle"></i>
                                    <span>Mengelola data produk dan kategori</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-blue-500 fas fa-check-circle"></i>
                                    <span>Mengelola data supplier</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-blue-500 fas fa-check-circle"></i>
                                    <span>Mengawasi operasional gudang</span>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="p-6 border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/30 rounded-xl">
                            <h3 class="flex items-center mb-4 font-semibold text-green-800 dark:text-green-300">
                                <i class="mr-3 fas fa-user"></i>
                                <span>Staff Gudang</span>
                            </h3>
                            <ul class="space-y-2 text-sm text-green-700 dark:text-green-400">
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-green-500 fas fa-check-circle"></i>
                                    <span>Melakukan transaksi stok masuk/keluar</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-green-500 fas fa-check-circle"></i>
                                    <span>Melihat data produk dan stok</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-green-500 fas fa-check-circle"></i>
                                    <span>Membuat laporan transaksi</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="mt-1 mr-2 text-green-500 fas fa-check-circle"></i>
                                    <span>Tugas operasional sehari-hari</span>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Account Status Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-info-circle dark:text-blue-400"></i>
                        <span>Status Akun</span>
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                <i class="mr-1 fas fa-check-circle"></i>
                                Aktif
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Bergabung</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                <i class="mr-2 fas fa-calendar-day"></i>
                                {{ $user->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Update</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                <i class="mr-2 fas fa-history"></i>
                                {{ $user->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="flex items-center mb-6 text-lg font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-bolt dark:text-blue-400"></i>
                        <span>Aksi Cepat</span>
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="relative flex items-center justify-center px-4 py-3 text-blue-700 transition-colors group bg-blue-50 rounded-xl hover:bg-blue-100">
                            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                            <i class="mr-3 fas fa-edit"></i>
                            <span class="font-medium">Edit Informasi</span>
                        </a>

                        <button onclick="resetPassword()"
                                class="relative flex items-center justify-center w-full px-4 py-3 text-yellow-700 transition-colors group bg-yellow-50 rounded-xl hover:bg-yellow-100">
                            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                            <i class="mr-3 fas fa-key"></i>
                            <span class="font-medium">Reset Password</span>
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="relative flex items-center justify-center w-full px-4 py-3 text-gray-700 transition-colors group bg-gray-50 rounded-xl hover:bg-gray-100">
                            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                            <i class="mr-3 fas fa-arrow-left"></i>
                            <span class="font-medium">Kembali ke Daftar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetPassword() {
            if (confirm('Apakah Anda yakin ingin mereset password pengguna ini?')) {
                alert('Fitur reset password akan segera tersedia');
            }
        }
    </script>
@endsection
