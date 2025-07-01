<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.description') }}">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <!-- Favicon -->
     <link rel="icon" href="{{ get_favicon_url() }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo Aplikasi -->
            <div class="flex justify-center">
                @if(get_favicon_url())
                    <img src="{{ get_favicon_url() }}"
                         alt="{{ config('app.name') }} Logo"
                         class="w-16 h-16 p-1 bg-white rounded-full shadow-lg dark:bg-gray-800">
                @else
                    <div class="p-3 bg-blue-600 rounded-full shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                @endif
            </div>

            <h2 class="mt-6 text-2xl font-bold text-center text-gray-900 dark:text-white">
                {{ $pageTitle ?? 'Selamat Datang' }}
            </h2>

            <p class="mt-2 text-sm text-center text-gray-600 dark:text-gray-300">
                {{ $pageDescription ?? 'Silakan Lakukan login atau Register' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10 dark:bg-gray-800">
                @yield('content')

            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
