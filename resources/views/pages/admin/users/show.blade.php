@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('content')
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Users</a>
            <span>/</span>
            <span>Detail User</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail User</h1>
                <p class="text-gray-600 dark:text-gray-400">Informasi lengkap pengguna {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Hapus User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-user mr-2"></i>Informasi User
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Nama Lengkap
                            </label>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Email
                            </label>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                Role
                            </label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                @if($user->role === 'Admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @elseif($user->role === 'Manajer Gudang') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @endif">
                                {{ $user->role }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                User ID
                            </label>
                            <p class="text-lg text-gray-900 dark:text-white">#{{ $user->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Role Permissions --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-shield-alt mr-2"></i>Hak Akses Role
                    </h2>

                    @if($user->role === 'Admin')
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border-l-4 border-purple-500">
                            <h3 class="font-medium text-purple-800 dark:text-purple-300 mb-2">Administrator</h3>
                            <ul class="text-sm text-purple-700 dark:text-purple-400 space-y-1">
                                <li>• Akses penuh ke semua fitur sistem</li>
                                <li>• Mengelola pengguna dan hak akses</li>
                                <li>• Mengelola semua data master</li>
                                <li>• Melihat semua laporan dan analitik</li>
                                <li>• Mengatur konfigurasi sistem</li>
                            </ul>
                        </div>
                    @elseif($user->role === 'Manajer Gudang')
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border-l-4 border-blue-500">
                            <h3 class="font-medium text-blue-800 dark:text-blue-300 mb-2">Manajer Gudang</h3>
                            <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
                                <li>• Mengelola stok dan transaksi gudang</li>
                                <li>• Melihat dan membuat laporan</li>
                                <li>• Mengelola data produk dan kategori</li>
                                <li>• Mengelola data supplier</li>
                                <li>• Mengawasi operasional gudang</li>
                            </ul>
                        </div>
                    @else
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border-l-4 border-green-500">
                            <h3 class="font-medium text-green-800 dark:text-green-300 mb-2">Staff Gudang</h3>
                            <ul class="text-sm text-green-700 dark:text-green-400 space-y-1">
                                <li>• Melakukan transaksi stok masuk/keluar</li>
                                <li>• Melihat data produk dan stok</li>
                                <li>• Membuat laporan transaksi</li>
                                <li>• Tugas operasional sehari-hari</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Information --}}
        <div class="space-y-6">
            {{-- Account Status --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-info-circle mr-2"></i>Status Akun
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full">
                                Aktif
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Bergabung</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $user->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Terakhir Update</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                {{ $user->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Informasi
                        </a>

                        <button onclick="resetPassword()"
                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetPassword() {
            if (confirm('Apakah Anda yakin ingin mereset password user ini?')) {
                alert('Fitur reset password akan segera tersedia');
            }
        }
    </script>
@endsection
