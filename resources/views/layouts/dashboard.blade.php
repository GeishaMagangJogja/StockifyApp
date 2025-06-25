<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Stockify')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- (Font dan script dark mode bisa diletakkan di sini) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">

    {{-- Navbar selalu tampil untuk semua user --}}
    <x-navbar-dashboard/>

    @auth
        {{-- ======================================================= --}}
        {{-- TAMPILAN JIKA USER SUDAH LOGIN (DENGAN SIDEBAR) --}}
        {{-- ======================================================= --}}
        <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
            
            {{-- Logika "saklar" untuk memilih sidebar mana yang akan ditampilkan --}}
            @if (Auth::user()->role === 'Admin')
                <x-sidebar.admin-sidebar />
            @elseif (Auth::user()->role === 'Manajer Gudang')
                <x-sidebar.manajergudang-sidebar />
            {{-- Tambahkan role lain jika perlu --}}
            {{-- @elseif (Auth::user()->role === 'Staff Gudang')
                <x-sidebar.staff-sidebar /> --}}
            @endif

            {{-- Konten utama dengan margin kiri untuk memberi ruang pada sidebar --}}
            <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
                <main class="p-4 md:p-6">
                    @yield('content')
                </main>
                <x-footer-dashboard/>
            </div>
        </div>
    @endauth

    @guest
        {{-- ======================================================= --}}
        {{-- TAMPILAN JIKA USER BELUM LOGIN (TANPA SIDEBAR) --}}
        {{-- ======================================================= --}}
        <div class="pt-16">
            {{-- Konten utama langsung mengambil seluruh lebar layar --}}
            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    @endguest

    @stack('scripts')
</body>
</html>