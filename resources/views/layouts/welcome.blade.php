@extends('layouts.dashboard')

@section('title', 'Selamat Datang di Stockify')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-50 dark:from-gray-900 dark:to-gray-800">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden opacity-10 dark:opacity-20">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob dark:bg-blue-800"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000 dark:bg-purple-800"></div>
        <div class="absolute bottom-1/4 left-1/2 w-64 h-64 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000 dark:bg-pink-800"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <!-- Logo/Icon -->
        <div class="mb-8 transition-transform duration-500 transform hover:scale-110">
            <div class="p-5 bg-blue-600 rounded-2xl shadow-xl dark:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl font-extrabold tracking-tight text-center text-gray-900 sm:text-5xl md:text-6xl dark:text-white">
            <span class="block">Selamat Datang di</span>
            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-400 dark:from-blue-400 dark:to-blue-300">Stockify</span>
        </h1>

        <!-- Description -->
        <p class="max-w-2xl mx-auto mt-6 text-xl text-center text-gray-600 dark:text-gray-300">
            Sistem manajemen inventaris modern yang membantu Anda mengelola stok dengan mudah dan efisien.
        </p>

        <!-- Features Grid -->
        <div class="grid max-w-4xl grid-cols-1 gap-6 mt-12 sm:grid-cols-2 lg:grid-cols-3">
            <div class="p-6 transition-all duration-300 bg-white rounded-lg shadow-md hover:shadow-xl dark:bg-gray-800 hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Manajemen Produk</h3>
                <p class="text-gray-600 dark:text-gray-400">Kelola produk dengan mudah, termasuk stok, kategori, dan detail lainnya.</p>
            </div>

            <div class="p-6 transition-all duration-300 bg-white rounded-lg shadow-md hover:shadow-xl dark:bg-gray-800 hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-purple-600 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Laporan Real-time</h3>
                <p class="text-gray-600 dark:text-gray-400">Pantau semua aktivitas inventaris dengan laporan yang selalu diperbarui.</p>
            </div>

            <div class="p-6 transition-all duration-300 bg-white rounded-lg shadow-md hover:shadow-xl dark:bg-gray-800 hover:-translate-y-1">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-green-600 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Keamanan Terjamin</h3>
                <p class="text-gray-600 dark:text-gray-400">Data Anda aman dengan sistem keamanan berlapis dan enkripsi canggih.</p>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col mt-16 space-y-4 sm:space-y-0 sm:space-x-4 sm:flex-row">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-white transition-all duration-300 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 hover:shadow-lg">
                Masuk ke Akun
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-blue-700 transition-all duration-300 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 hover:shadow-lg">
                Daftar Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                </svg>
            </a>
        </div>

        <!-- Testimonials (Optional) -->
        <div class="max-w-3xl mx-auto mt-16">
            <div class="relative">
                <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl opacity-10 dark:opacity-20"></div>
                <div class="relative p-8 bg-white rounded-xl shadow-lg dark:bg-gray-800">
                    <div class="flex items-center">
                        <img class="w-12 h-12 rounded-full" src="https://i.pinimg.com/736x/3b/44/10/3b4410e67b15f8272026cbd411b6e57d.jpg" alt="Testimonial">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Ronaldo Pattripan</h4>
                            <p class="text-gray-600 dark:text-gray-400">Manajer Gudang, Seven Inc</p>
                        </div>
                    </div>
                    <blockquote class="mt-4 text-gray-700 dark:text-gray-300">
                        <p>"Stockify telah mengubah cara kami mengelola inventaris. Antarmuka yang intuitif dan fitur pelaporan yang lengkap membuat pekerjaan kami lebih efisien."</p>
                    </blockquote>
                    <div class="flex mt-4 space-x-1 text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endsection
