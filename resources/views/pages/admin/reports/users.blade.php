@extends('layouts.dashboard')

@section('title', 'Laporan Aktivitas Pengguna')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Laporan Aktivitas Pengguna</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm table-auto border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Nama</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Email</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Role</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Terdaftar Sejak</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-100">
                @forelse($users as $user)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $user->role == 'admin' ? 'bg-blue-600 text-white' : 'bg-gray-600 text-white' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-gray-500 dark:text-gray-400">Tidak ada data pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
