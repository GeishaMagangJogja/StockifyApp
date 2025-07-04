@extends('layouts.auth')

@section('title', 'Login')
@section('pageTitle', 'Masuk Kembali')
@section('page-description', 'Isi kredensial Anda untuk mengakses dashboard.')

@section('content')
<form id="loginForm" class="space-y-6">
    @csrf
    <div id="alertContainer" class="hidden"></div>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
        <div class="mt-1">
            <input type="email" id="email" name="email" required
            class="block w-full px-4 py-3 bg-gray-50 text-gray-900 dark:bg-slate-800 dark:text-white border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent sm:text-sm"
            placeholder="anda@email.com">
        </div>
        <span class="hidden text-sm text-red-500" id="email-error"></span>
    </div>

    <!-- Password Field -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
        <div class="mt-1 relative">
            <input type="password" id="password" name="password" required
            class="block w-full px-4 py-3 bg-gray-50 text-gray-900 dark:bg-slate-800 dark:text-white border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm placeholder-gray-400 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent sm:text-sm"
            placeholder="••••••••">
            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600" onclick="togglePassword()">
                <svg id="eye-open" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg id="eye-closed" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" /></svg>
            </button>
        </div>
        <span class="hidden text-sm text-red-500" id="password-error"></span>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-slate-600 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Ingat saya</label>
        </div>
    </div>

    <!-- Submit Button (Gaya Dashboard) -->
    <div>
        <button type="submit" id="loginButton"
                class="group w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-0.5">
            <svg id="loadingIcon" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span id="loginText">Masuk</span>
        </button>
    </div>

    <div class="text-center text-sm pt-4">
        <p class="text-gray-600 dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Daftar sekarang
            </a>
        </p>
    </div>
</form>
@endsection

@vite('resources/js/auth/login.js')