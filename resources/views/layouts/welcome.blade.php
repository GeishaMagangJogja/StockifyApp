@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-50 dark:from-gray-900 dark:to-gray-800">
    <!-- Navigation -->
    <nav class="px-6 py-4 border-b border-gray-200 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md dark:border-gray-700">
        <div class="container flex items-center justify-between mx-auto">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                @if(config('app.logo'))
                    <img src="{{ asset('storage/' . config('app.logo')) }}"
                         alt="{{ config('app.name') }} Logo"
                         class="w-10 h-10 rounded-lg">
                @else
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                @endif
                <span class="text-xl font-bold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 md:flex">
                <a href="#features" class="font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Fitur</a>
                <a href="#testimonials" class="font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Testimoni</a>
            </div>

            <!-- Auth Links -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative px-6 pt-24 pb-20 sm:pt-32 sm:pb-28 lg:pt-40 lg:pb-32">
        <div class="container mx-auto text-center">
            <!-- Logo/Icon -->
            <div class="flex justify-center mb-8">
                @if(config('app.logo'))
                    <img src="{{ asset('storage/' . config('app.logo')) }}"
                         alt="{{ config('app.name') }} Logo"
                         class="w-24 h-24 p-2 bg-white rounded-full shadow-xl dark:bg-gray-800">
                @else
                    <div class="p-5 bg-blue-600 rounded-full shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Headline -->
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                <span class="block text-gray-900 dark:text-white">Selamat Datang di</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-400 dark:from-blue-400 dark:to-blue-300">
                    {{ config('app.name') }}
                </span>
            </h1>

            <!-- Description -->
            <p class="max-w-3xl mx-auto mt-6 text-xl text-gray-600 dark:text-gray-300">
                {{ config('app.appDescription', 'Sistem manajemen inventaris modern yang membantu Anda mengelola stok dengan mudah dan efisien.') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col justify-center mt-10 space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                    Mulai Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-blue-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="px-6 py-16 bg-white dark:bg-gray-800 sm:py-24 lg:py-32">
        <div class="container mx-auto">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Fitur Unggulan
                </h2>
                <p class="max-w-2xl mx-auto mt-4 text-xl text-gray-600 dark:text-gray-300">
                    Solusi lengkap untuk manajemen inventaris Anda
                </p>
            </div>

            <div class="grid max-w-4xl grid-cols-1 gap-8 mx-auto mt-12 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="p-8 transition-all duration-300 bg-gray-50 rounded-xl dark:bg-gray-700/50 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 mb-6 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manajemen Produk</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        Kelola produk dengan mudah, termasuk stok, kategori, dan detail lainnya dalam satu platform terpusat.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 transition-all duration-300 bg-gray-50 rounded-xl dark:bg-gray-700/50 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 mb-6 text-purple-600 bg-purple-100 rounded-lg dark:bg-purple-900 dark:text-purple-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laporan Real-time</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        Pantau semua aktivitas inventaris dengan laporan yang selalu diperbarui secara real-time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 transition-all duration-300 bg-gray-50 rounded-xl dark:bg-gray-700/50 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-center justify-center w-12 h-12 mb-6 text-green-600 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Keamanan Terjamin</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        Data Anda aman dengan sistem keamanan berlapis dan enkripsi canggih.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="px-6 py-12 bg-gray-800 dark:bg-gray-900">
        <div class="container mx-auto">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="flex items-center space-x-2">
                    @if(config('app.logo'))
                        <img src="{{ asset('storage/' . config('app.logo')) }}"
                             alt="{{ config('app.name') }} Logo"
                             class="w-10 h-10 rounded-lg">
                    @endif
                    <span class="text-xl font-bold text-white">{{ config('app.name') }}</span>
                </div>
            </div>
            <div class="pt-8 mt-8 border-t border-gray-700 md:flex md:items-center md:justify-between">
                <p class="text-base text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>
@endsection
