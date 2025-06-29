@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Baris Pertama: Kartu Statistik Utama --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Card: Total Produk --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500/30">
                    <i class="fas fa-box fa-fw text-xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Total Supplier --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
            <div class="flex items-center">
                 <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500/30">
                    <i class="fas fa-truck fa-fw text-xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalSuppliers }}</p>
                </div>
            </div>
        </div>
        
        {{-- Card: Total Kategori --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
             <div class="flex items-center">
                <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-full dark:text-indigo-100 dark:bg-indigo-500/30">
                    <i class="fas fa-tags fa-fw text-xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Total Pengguna --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
            <div class="flex items-center">
                <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500/30">
                    <i class="fas fa-users fa-fw text-xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengguna</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris Kedua: Grafik --}}
    <div class="mb-6">
        <div class="p-6 bg-white rounded-lg shadow dark:bg-slate-800">
            <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Aktivitas Transaksi (7 Hari Terakhir)</h5>
            <div id="main-chart"></div>
        </div>
    </div>

    {{-- Baris Ketiga: Aktivitas Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Tabel: Transaksi Terbaru --}}
        <div class="bg-white rounded-lg shadow dark:bg-slate-800">
            <h5 class="px-6 py-4 text-xl font-bold text-gray-900 dark:text-white border-b dark:border-slate-700">Transaksi Terbaru</h5>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                        <tr class="border-b dark:border-slate-700">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $transaction->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }} - oleh {{ $transaction->user->name ?? 'N/A' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($transaction->type == 'Masuk')
                                    <span class="font-semibold text-green-600">+{{ $transaction->quantity }}</span>
                                @else
                                    <span class="font-semibold text-red-600">-{{ $transaction->quantity }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Tabel: Pengguna Terbaru --}}
        <div class="bg-white rounded-lg shadow dark:bg-slate-800">
            <h5 class="px-6 py-4 text-xl font-bold text-gray-900 dark:text-white border-b dark:border-slate-700">Pengguna Terbaru</h5>
             <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        @forelse ($recentUsers as $user)
                        <tr class="border-b dark:border-slate-700">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->role }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Memuat library ApexCharts dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mengambil data yang sudah disiapkan oleh controller
            const chartData = {!! json_encode($chartData) !!};

            const options = {
                series: [{
                    name: 'Barang Masuk',
                    data: chartData.incoming,
                    color: '#16A34A', // Hijau
                }, {
                    name: 'Barang Keluar',
                    data: chartData.outgoing,
                    color: '#3B82F6', // Biru
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                    },
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: chartData.categories,
                    labels: { style: { colors: '#6B7280' } }
                },
                yaxis: {
                    title: { text: 'Jumlah Unit', style: { color: '#6B7280' } },
                    labels: { style: { colors: '#6B7280' } }
                },
                grid: { borderColor: '#e7e7e720' },
                fill: { opacity: 1 },
                tooltip: {
                    y: { formatter: (val) => `${val} unit` },
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                },
                legend: { labels: { colors: '#6B7280' } }
            };

            const chart = new ApexCharts(document.querySelector("#main-chart"), options);
            chart.render();
        });
    </script>
@endpush