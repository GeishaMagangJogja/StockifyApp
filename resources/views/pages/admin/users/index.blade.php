@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')

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
                <span class="px-3 py-1 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-400 dark:bg-blue-900/50">
                    <i class="mr-2 fas fa-users"></i>Pengguna
                </span>
            </nav>

            <!-- Title Section -->
            <div class="flex flex-col items-start justify-between space-y-4 lg:flex-row lg:items-center lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        <i class="mr-3 text-blue-600 fas fa-users dark:text-blue-400"></i>
                        Kelola Pengguna
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Manajemen semua pengguna sistem
                    </p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                   class="group relative inline-flex items-center px-6 py-3 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-white/20 rounded-xl group-hover:opacity-100"></div>
                    <i class="mr-2 fas fa-plus"></i>
                    <span class="font-medium">Tambah Pengguna</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
        <!-- Total Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                        <i class="text-xl text-white fas fa-users"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900/50">
                        Total
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Admin Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-red-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-red-500 to-red-600 rounded-xl">
                        <i class="text-xl text-white fas fa-user-shield"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full dark:text-red-400 dark:bg-red-900/50">
                        Admin
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Admin Sistem</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $users->where('role', 'Admin')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Manager Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-blue-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                        <i class="text-xl text-white fas fa-user-tie"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:text-blue-400 dark:bg-blue-900/50">
                        Manajer
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Manajer Gudang</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $users->where('role', 'Manajer Gudang')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Staff Users Card -->
        <div class="relative overflow-hidden transition-all duration-300 transform bg-white shadow-lg group rounded-2xl hover:shadow-xl hover:-translate-y-1 dark:bg-gray-800">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-green-600/10"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 shadow-lg bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                        <i class="text-xl text-white fas fa-user"></i>
                    </div>
                    <div class="px-3 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full dark:text-green-400 dark:bg-green-900/50">
                        Staff
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Staff Gudang</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $users->where('role', 'Staff Gudang')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Main Content Card -->
    <div class="overflow-hidden bg-white shadow-xl rounded-2xl dark:bg-gray-800">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <div class="p-6">
                <div class="flex flex-col justify-between space-y-6 lg:flex-row lg:items-center lg:space-y-0">
                    <div class="space-y-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            <i class="mr-2 text-blue-600 fas fa-list dark:text-blue-400"></i>
                            Daftar Pengguna
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Kelola semua pengguna sistem dengan mudah
                        </p>
                    </div>

                    <!-- Enhanced Search Form -->
                    <form method="GET" action="{{ route('admin.users.index') }}"
                          class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:space-x-3 sm:space-y-0">
                        <div class="relative group">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan nama atau email..."
                                   class="w-full px-4 py-3 pl-12 pr-4 placeholder-gray-400 transition-all duration-200 bg-white border border-gray-300 sm:w-80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <i class="text-gray-400 transition-colors duration-200 group-focus-within:text-blue-500 fas fa-search"></i>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select name="role" class="px-4 py-3 pr-10 transition-all duration-200 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Role</option>
                                @foreach(['Admin', 'Manajer Gudang', 'Staff Gudang'] as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="px-6 py-3 font-medium text-white transition-all duration-200 shadow-lg bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 hover:shadow-xl">
                                <i class="mr-2 fas fa-search"></i>Cari
                            </button>
                            @if(request()->anyFilled(['search', 'role']))
                                <a href="{{ route('admin.users.index') }}"
                                   class="px-6 py-3 font-medium text-gray-700 transition-all duration-200 bg-gray-200 rounded-xl hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                    <i class="mr-2 fas fa-times"></i>Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="p-6">
            @if($users->count() > 0)
                <div class="overflow-hidden border border-gray-200 rounded-xl dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-user"></i>Pengguna
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-user-tag"></i>Role
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-circle"></i>Status
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-calendar-alt"></i>Bergabung
                                        </span>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right">
                                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            <i class="mr-2 fas fa-cog"></i>Aksi
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($users as $user)
                                    <tr class="transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="flex items-center justify-center w-12 h-12 transition-shadow duration-200 shadow-lg bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl group-hover:shadow-xl">
                                                        <span class="font-medium text-white">{{ substr($user->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $user->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([
                                                'inline-flex items-center px-2 py-1 text-xs font-semibold leading-5 rounded-full',
                                                'text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300' => $user->role == 'Admin',
                                                'text-blue-800 bg-blue-100 dark:bg-blue-900/50 dark:text-blue-300' => $user->role == 'Manajer Gudang',
                                                'text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300' => $user->role == 'Staff Gudang'
                                            ])>
                                                <i @class([
                                                    'mr-1 fas',
                                                    'fa-user-shield' => $user->role == 'Admin',
                                                    'fa-user-tie' => $user->role == 'Manajer Gudang',
                                                    'fa-user' => $user->role == 'Staff Gudang'
                                                ])></i>
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                                <i class="mr-1 fas fa-check-circle"></i>
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <i class="mr-2 text-xs text-gray-400 fas fa-calendar"></i>
                                                {{ $user->created_at->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-1">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   class="inline-flex items-center justify-center text-blue-600 transition-all duration-200 rounded-lg w-9 h-9 hover:bg-blue-50 dark:hover:bg-gray-700 dark:text-blue-400 hover:scale-110"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                   class="inline-flex items-center justify-center transition-all duration-200 rounded-lg w-9 h-9 text-amber-600 hover:bg-amber-50 dark:hover:bg-gray-700 dark:text-amber-400 hover:scale-110"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.users.delete', $user) }}"
                                                   class="inline-flex items-center justify-center text-red-600 transition-all duration-200 rounded-lg w-9 h-9 hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400 hover:scale-110"
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                <div class="mt-8">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="py-16 text-center">
                    <div class="relative w-32 h-32 mx-auto mb-8">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800"></div>
                        <div class="relative flex items-center justify-center w-full h-full">
                            <i class="text-5xl text-gray-400 fas fa-user-slash"></i>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Belum ada pengguna</h3>
                        <p class="max-w-md mx-auto text-gray-500 dark:text-gray-400">
                            Mulai mengelola sistem Anda dengan menambahkan pengguna pertama.
                        </p>
                        <div class="pt-4">
                            <a href="{{ route('admin.users.create') }}"
                               class="inline-flex items-center px-8 py-4 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="mr-3 fas fa-plus"></i>
                                <span class="font-semibold">Tambah Pengguna Pertama</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
