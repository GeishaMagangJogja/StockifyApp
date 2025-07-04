<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.description') }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ get_favicon_url() }}" type="image/x-icon">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    {{-- Menggunakan Vite untuk CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Script untuk menerapkan dark mode lebih awal --}}
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-900">
    <div class="flex min-h-screen">
        <!-- Panel Kiri (Branding) - Terinspirasi dari dashboard -->
        <div class="hidden lg:flex w-1/2 items-center justify-center bg-gradient-to-br from-blue-600 to-purple-700 p-12 text-white relative overflow-hidden">
            <div class="z-10 text-center">
                <a href="/" class="inline-block mb-8">
                     @if(get_favicon_url())
                        <img src="{{ get_favicon_url() }}" alt="{{ config('app.name') }} Logo" class="w-24 h-24 p-2 bg-white/20 rounded-full shadow-xl backdrop-blur-sm">
                    @else
                        <div class="p-4 bg-white/20 rounded-full shadow-xl backdrop-blur-sm">
                            <i class="fa-solid fa-boxes-stacked text-5xl text-white"></i>
                        </div>
                    @endif
                </a>
                <h1 class="text-4xl font-bold tracking-tight">
                    Kelola Inventaris Anda
                </h1>
                <p class="mt-4 text-lg text-blue-100 max-w-md mx-auto">
                    Platform terintegrasi untuk manajemen stok yang lebih cerdas dan efisien.
                </p>
            </div>
             <!-- Elemen dekoratif -->
            <div class="absolute -top-16 -left-16 w-64 h-64 bg-white/10 rounded-full opacity-50"></div>
            <div class="absolute -bottom-24 -right-10 w-80 h-80 bg-white/10 rounded-full opacity-50"></div>
        </div>

        <!-- Panel Kanan (Form) -->
        <div class="flex flex-1 flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md space-y-8">
                 <div class="text-center">
                     <a href="/" class="lg:hidden inline-block mb-6">
                        @if(get_favicon_url())
                             <img src="{{ get_favicon_url() }}" alt="{{ config('app.name') }} Logo" class="mx-auto w-16 h-16 p-1 bg-white dark:bg-slate-800 rounded-full shadow-lg">
                        @endif
                     </a>
                     <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $pageTitle ?? 'Selamat Datang' }}
                     </h2>
                     <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ $pageDescription ?? 'Silakan Lakukan login atau Register' }}
                     </p>
                 </div>

                <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-8 sm:p-10">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>