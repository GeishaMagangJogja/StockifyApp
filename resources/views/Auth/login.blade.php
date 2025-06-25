@extends('layouts.auth')

@section('title', 'Login - Stockify')
@section('page-title', 'Masuk ke Akun Anda')
@section('page-description', 'Silakan masuk dengan kredensial Anda untuk mengakses dashboard')

@section('content')
<form
    id="loginForm"
    class="space-y-6"
    method="POST"
    action="{{ route('login.process') }}"
>
    @csrf

    <div id="alertContainer" class="hidden"></div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Icon -->
            </div>
            <input
                type="email"
                id="email"
                name="email"
                required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                placeholder="Masukkan email Anda"
                value="{{ old('email') }}"
            >
        </div>
        <span class="text-red-500 text-sm hidden" id="email-error"></span>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <div class="relative">
            <input
                type="password"
                id="password"
                name="password"
                required
                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                placeholder="Masukkan password Anda"
            >
            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                <!-- Toggle icons -->
            </button>
        </div>
        <span class="text-red-500 text-sm hidden" id="password-error"></span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="remember" class="ml-2 text-sm text-gray-700">Ingat saya</label>
        </div>
    </div>

    <div>
        <button
            type="submit"
            id="loginButton"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <!-- loginIcon & loadingIcon -->
            </span>
            <span id="loginText">Masuk</span>
        </button>
    </div>

    <div class="text-center">
        <p class="text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">Daftar sekarang</a>
        </p>
    </div>
</form>
@endsection

@vite('resources/js/auth/login.js')
