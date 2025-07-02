<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.description', 'Sistem manajemen inventaris modern') }}">

    <title>{{ config('app.name', 'Stockify') }} - Sistem Manajemen Inventaris</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ get_favicon_url() }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark mode detection -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 dark:bg-gray-900 dark:text-gray-200">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 border-b bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-gray-200/50 dark:border-gray-800/50">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    @if(config('app.logo'))
                        <img src="{{ asset('storage/' . config('app.logo')) }}"
                             alt="{{ config('app.name') }} Logo"
                             class="rounded-lg w-9 h-9">
                    @else
                        <div class="p-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="items-center hidden space-x-8 md:flex">
                    <a href="#features" class="text-sm font-medium text-gray-600 transition-colors duration-200 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Fitur</a>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 transition-colors duration-200 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white transition-all duration-300 rounded-lg shadow bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 hover:shadow-md">Daftar Gratis</a>
                </div>

                <!-- Mobile menu button -->
                <button class="p-2 text-gray-600 rounded-md md:hidden dark:text-gray-300 focus:outline-none" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 overflow-hidden md:pt-32 md:pb-24 lg:pt-40 lg:pb-32">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-purple-50/50 dark:from-gray-800/50 dark:to-gray-900/50"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmNWY1ZjUiIGZpbGwtb3BhY2l0eT0iMC4yIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxIDEpIj48cmVjdCB3aWR0aD0iNTgiIGhlaWdodD0iNTgiIHg9IjAiIHk9IjAiIHJ4PSI2Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10 dark:opacity-5"></div>
        </div>

        <div class="container relative z-10 px-4 mx-auto sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Logo/Icon -->
                <div class="flex justify-center mb-8">
                    @if(config('app.logo'))
                        <img src="{{ asset('storage/' . config('app.logo')) }}"
                             alt="{{ config('app.name') }} Logo"
                             class="w-20 h-20 p-3 bg-white shadow-lg rounded-2xl dark:bg-gray-800">
                    @else
                        <div class="p-4 shadow-lg rounded-2xl bg-gradient-to-r from-blue-600 to-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Headline -->
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    <span class="block text-gray-900 dark:text-white">Kelola Inventaris dengan</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 dark:from-blue-400 dark:to-blue-300">
                        Lebih Mudah & Efisien
                    </span>
                </h1>

                <!-- Description -->
                <p class="max-w-2xl mx-auto mt-6 text-xl leading-relaxed text-gray-600 dark:text-gray-300">
                    {{ config('app.appDescription', 'Sistem manajemen inventaris modern yang membantu Anda mengelola stok dengan mudah dan efisien.') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col justify-center mt-10 space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-white transition-all duration-300 transform shadow-lg bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl hover:from-blue-700 hover:to-blue-600 hover:shadow-xl hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Mulai Sekarang - Gratis
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-50 dark:bg-gray-900 sm:py-24 lg:py-32">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <span class="inline-block px-3 py-1 text-sm font-medium text-blue-600 rounded-full bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400">Fitur Unggulan</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Solusi Lengkap untuk Bisnis Anda
                </h2>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-300">
                    Kelola inventaris dengan lebih efisien dan hemat waktu dengan fitur-fitur canggih kami.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-16 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="relative p-8 transition-all duration-300 bg-white group dark:bg-gray-800 rounded-xl hover:shadow-lg hover:-translate-y-2">
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 rounded-xl group-hover:opacity-100 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700/20 dark:to-gray-800/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-center mb-6 text-blue-600 bg-blue-100 w-14 h-14 rounded-xl dark:bg-blue-900/50 dark:text-blue-300">
                            <i class="text-2xl fas fa-boxes"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Manajemen Produk</h3>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Kelola produk dengan mudah, termasuk stok, kategori, dan detail lainnya dalam satu platform terpusat.
                        </p>
                        <div class="mt-6">
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Pelajari lebih lanjut
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="relative p-8 transition-all duration-300 bg-white group dark:bg-gray-800 rounded-xl hover:shadow-lg hover:-translate-y-2">
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 rounded-xl group-hover:opacity-100 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700/20 dark:to-gray-800/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-center mb-6 text-purple-600 bg-purple-100 w-14 h-14 rounded-xl dark:bg-purple-900/50 dark:text-purple-300">
                            <i class="text-2xl fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Analisis Real-time</h3>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Pantau semua aktivitas inventaris dengan laporan dan analisis yang selalu diperbarui secara real-time.
                        </p>
                        <div class="mt-6">
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Pelajari lebih lanjut
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="relative p-8 transition-all duration-300 bg-white group dark:bg-gray-800 rounded-xl hover:shadow-lg hover:-translate-y-2">
                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 rounded-xl group-hover:opacity-100 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700/20 dark:to-gray-800/20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-center mb-6 text-green-600 bg-green-100 w-14 h-14 rounded-xl dark:bg-green-900/50 dark:text-green-300">
                            <i class="text-2xl fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Keamanan Data</h3>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">
                            Data Anda aman dengan sistem keamanan berlapis, enkripsi canggih, dan backup otomatis.
                        </p>
                        <div class="mt-6">
                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                                Pelajari lebih lanjut
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <!-- Screenshot Section -->
    <section class="py-16 bg-white dark:bg-gray-800 sm:py-24 lg:py-32">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <span class="inline-block px-3 py-1 text-sm font-medium text-blue-600 rounded-full bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400">Antarmuka Modern</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Dashboard yang Intuitif
                </h2>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-300">
                    Dirancang untuk kemudahan penggunaan dengan tampilan yang bersih dan informatif.
                </p>
            </div>

            <div class="mt-16">
                <div class="relative max-w-5xl mx-auto overflow-hidden shadow-2xl rounded-xl">
                    <div class="absolute top-0 left-0 right-0 flex items-center h-8 px-3 space-x-2 bg-gray-100 dark:bg-gray-700">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    </div>
                    <img src="images/dashboard.png"
                         alt="Dashboard Screenshot"
                         class="w-full border border-gray-200 dark:border-gray-700">
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="pt-16 pb-12 bg-gray-800 dark:bg-gray-900">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-5">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3">
                        @if(config('app.logo'))
                            <img src="{{ asset('storage/' . config('app.logo')) }}"
                                 alt="{{ config('app.name') }} Logo"
                                 class="w-10 h-10 rounded-lg">
                        @endif
                        <span class="text-xl font-bold text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="mt-4 text-sm text-gray-400">
                        Sistem manajemen inventaris modern yang membantu Anda mengelola stok dengan mudah dan efisien.
                    </p>
                </div>
            </div>
            <div class="pt-8 mt-16 border-t border-gray-700">
                <p class="text-sm text-gray-400 md:text-center">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
