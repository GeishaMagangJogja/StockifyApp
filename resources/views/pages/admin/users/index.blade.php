@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pengguna</h1>
                <p class="text-gray-600 dark:text-gray-400">Manajemen semua pengguna sistem</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-plus"></i>Tambah Pengguna
            </a>
        </div>
    </div>

    @include('partials.alert')

    <div class="bg-white rounded-lg shadow dark:bg-slate-800">
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Cari Pengguna</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama atau email..."
                           class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select name="role" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Role</option>
                        @foreach(['Admin', 'Manajer Gudang', 'Staff Gudang'] as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-search"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Pengguna</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Role</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Bergabung</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-800 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full dark:bg-slate-600">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' => $user->role == 'Admin',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' => $user->role == 'Manajer Gudang',
                                    'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' => $user->role == 'Staff Gudang'
                                ])>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-blue-500 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-yellow-500 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.users.delete', $user) }}" class="p-2 text-red-500 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50" title="Hapus">
    <i class="fas fa-trash"></i>
</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="mx-auto mb-2 text-4xl fas fa-user-slash"></i>
                                <p class="text-lg">Tidak ada pengguna ditemukan</p>
                                @if(request()->anyFilled(['search', 'role']))
                                    <a href="{{ route('admin.users.index') }}" class="mt-2 text-blue-500 hover:underline">Reset pencarian</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
