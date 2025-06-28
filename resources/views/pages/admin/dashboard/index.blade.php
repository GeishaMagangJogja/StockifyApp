@extends('layouts.dashboard') {{-- Ganti jika layout utamamu beda --}}

@section('title', 'Admin Dashboard')

@section('content')
    {{-- Baris 1: Ringkasan Angka --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card: Total Produk --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Transaksi Masuk (30 hari) --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Transaksi Masuk (30 hari)</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $incomingCount }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Transaksi Keluar (30 hari) --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 16V12l-3-3M9 7H5a2 2 0 00-2 2v6a2 2 0 002 2h8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Transaksi Keluar (30 hari)</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $outgoingCount }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Pengguna Terdaftar --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V8H2v12h5m5-16h4l3 4h-10l3-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Pengguna Terdaftar</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                        {{ \App\Models\User::count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris 2: Grafik & Aktivitas Pengguna --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        {{-- Grafik Transaksi 7 Hari --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow lg:col-span-2 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Grafik Transaksi 7 Hari</h5>
            <div id="admin-stock-chart"></div>
        </div>

        {{-- Aktivitas Pengguna Terbaru --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Aktivitas Pengguna Terbaru</h5>
            <ul class="space-y-4">
                @forelse($recentUsers as $user)
                    <li class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                        <span class="text-sm text-gray-400 dark:text-gray-500">{{ $user->updated_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const data = @json($chartData);

    const options = {
        series: [
            { name: 'Masuk', data: data.incoming },
            { name: 'Keluar', data: data.outgoing },
        ],
        chart: { type: 'line', height: 300 },
        xaxis: { categories: data.categories }}:
        

        const options = {
            series: [
                { name: 'Masuk', data: data.incoming },
                { name: 'Keluar', data: data.outgoing },
            ],
            chart: { type: 'line', height: 300 },
            xaxis: { categories: data.categories }
        };

        new ApexCharts(document.querySelector("#admin-stock-chart"), options).render();
    </script>
@endpush
