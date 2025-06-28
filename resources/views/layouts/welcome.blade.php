
@extends('layouts.dashboard')

@section('title', 'Selamat Datang di Stockify')

@section('content')
    <div class="flex items-center justify-center h-screen" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 lg:text-5xl dark:text-white">
                Selamat Datang di <span class="text-blue-600 dark:text-blue-500">Stockify</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                Manajemen inventaris menjadi lebih mudah. Silakan login untuk memulai.
            </p>
            <div class="mt-8">
                <a href="{{ route('login') }}" class="px-6 py-3 mr-4 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 font-semibold text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Sign Up
                </a>
            </div>
        </div>
    </div>
@endsection
