@extends('layouts.dashboard')

@section('title', 'Konfirmasi Hapus Pengguna')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus Pengguna</h1>
        <p class="text-gray-600 dark:text-gray-400">Anda akan menghapus pengguna dari sistem</p>
    </div>

    <div class="bg-white rounded-lg shadow dark:bg-slate-800">
        <div class="p-6">
            <div class="flex flex-col items-center p-6 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/50">
                    <i class="text-2xl text-red-500 fas fa-exclamation-triangle dark:text-red-400"></i>
                </div>
                <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Hapus Pengguna?</h3>

                <div class="w-full max-w-md mb-6">
                    <div class="p-4 mb-4 bg-gray-100 rounded-lg dark:bg-slate-700">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full dark:bg-slate-600">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300">
                        Pengguna ini memiliki role <span class="font-semibold">{{ $user->role }}</span> dan bergabung pada
                        <span class="font-semibold">{{ $user->created_at->format('d M Y') }}</span>.
                    </p>
                </div>

                <p class="mb-6 text-red-500 dark:text-red-400">
                    <i class="fas fa-exclamation-circle"></i> Tindakan ini tidak dapat dibatalkan. Semua data terkait pengguna ini akan dihapus.
                </p>

                <div class="flex justify-center gap-4">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                            <i class="mr-2 fas fa-trash"></i> Ya, Hapus
                        </button>
                    </form>

                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                        <i class="mr-2 fas fa-times"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
