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
                {{ config('app.description', 'Sistem manajemen inventaris modern yang membantu Anda mengelola stok dengan mudah dan efisien.') }}
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

    <!-- Testimonials Section -->
    <div id="testimonials" class="px-6 py-16 bg-gray-50 dark:bg-gray-700/30 sm:py-24 lg:py-32">
        <div class="container mx-auto">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Apa Kata Mereka
                </h2>
                <p class="max-w-2xl mx-auto mt-4 text-xl text-gray-600 dark:text-gray-300">
                    Testimoni dari pelanggan yang puas dengan layanan kami
                </p>
            </div>

            <div class="grid max-w-4xl grid-cols-1 gap-8 mx-auto mt-12 lg:grid-cols-2">
                <!-- Testimonial 1 -->
                <div class="p-8 transition-all duration-300 bg-white rounded-xl dark:bg-gray-800 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Testimonial">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Budi Santoso</h4>
                            <p class="text-gray-600 dark:text-gray-300">Pemilik Toko ABC</p>
                        </div>
                    </div>
                    <blockquote class="mt-4 text-gray-700 dark:text-gray-300">
                        <p>"Sistem ini sangat membantu bisnis saya. Sekarang saya bisa mengelola stok dengan lebih efisien dan laporannya sangat detail."</p>
                    </blockquote>
                    <div class="flex mt-4 space-x-1 text-yellow-400">
                        @for ($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="p-8 transition-all duration-300 bg-white rounded-xl dark:bg-gray-800 hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full" src="https://randomuser.me/api/portraits/women/44.jpg" alt="Testimonial">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Anita Rahayu</h4>
                            <p class="text-gray-600 dark:text-gray-300">Manajer Gudang XYZ</p>
                        </div>
                    </div>
                    <blockquote class="mt-4 text-gray-700 dark:text-gray-300">
                        <p>"Antarmuka yang sangat intuitif dan mudah digunakan. Pelatihan untuk tim kami hanya membutuhkan waktu singkat."</p>
                    </blockquote>
                    <div class="flex mt-4 space-x-1 text-yellow-400">
                        @for ($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
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
                <div class="flex mt-8 space-x-6 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="pt-8 mt-8 border-t border-gray-700 md:flex md:items-center md:justify-between">
                <p class="text-base text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
                <div class="flex mt-8 space-x-6 md:mt-0">
                    <a href="#" class="text-sm text-gray-400 hover:text-white">Privacy Policy</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-white">Terms of Service</a>
                    <a href="#" class="text-sm text-gray-400 hover:text-white">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
