@extends('layouts.auth')

@section('title', 'Register - Stockify')
@section('page-title', 'Buat Akun Baru')
@section('page-description', 'Daftar untuk bergabung dengan tim Stockify')

@section('content')
<form
    id="registerForm"
    class="space-y-6"
    method="POST"
    action="{{ route('register.process') }}"
>
    @csrf

    <!-- Alert Container -->
    <div id="alertContainer" class="hidden"></div>

    <!-- Name Field -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Nama Lengkap
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Icon -->
            </div>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="100"
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                placeholder="Masukkan nama lengkap"
            >
        </div>
        <span class="text-red-500 text-sm hidden" id="name-error"></span>
    </div>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <!-- Icon -->
            </div>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                placeholder="Masukkan email Anda"
            >
        </div>
        <span class="text-red-500 text-sm hidden" id="email-error"></span>
    </div>

    <!-- Password Field -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
        </label>
        <div class="relative">
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="6"
                class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                placeholder="Minimal 6 karakter"
            >
            <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                onclick="togglePassword('password')"
            >
                <!-- Toggle icons -->
            </button>
        </div>
        <span class="text-red-500 text-sm hidden" id="password-error"></span>
        <p class="mt-1 text-xs text-gray-500">Password harus minimal 6 karakter</p>
    </div>

    <!-- Submit Button -->
    <div>
        <button
            type="submit"
            id="registerButton"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <!-- registerIcon / loadingIcon -->
            </span>
            <span id="registerText">Daftar Sekarang</span>
        </button>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Masuk di sini
            </a>
        </p>
    </div>
</form>
@endsection

@vite('resources/js/auth/register.js')
