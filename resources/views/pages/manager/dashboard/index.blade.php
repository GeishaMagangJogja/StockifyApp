@extends('layouts.manager')

@section('title', 'Dashboard Manajer Gudang')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Total Produk
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($totalProducts) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Stok Rendah
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($lowStockProducts) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incoming Transactions -->
        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Barang Masuk (30 hari)
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($incomingTransactions) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outgoing Transactions -->
        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-red-100 rounded-lg dark:bg-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">
                                Barang Keluar (30 hari)
                            </dt>
                            <dd>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($outgoingTransactions) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement Chart -->
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Perpindahan Stok (7 Hari Terakhir)
            </h3>
            <div class="h-80">
                <canvas id="stockMovementChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Transaksi Terbaru
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Produk
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Jenis
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Jumlah
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $transaction->date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->product->name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->product->sku }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($transaction->type === 'Masuk')
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                    Masuk
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                    Keluar
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ number_format($transaction->quantity) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($transaction->status === 'Diterima' || $transaction->status === 'Dikeluarkan')
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                    {{ $transaction->status }}
                                </span>
                                @elseif ($transaction->status === 'Pending')
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ $transaction->status }}
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                    {{ $transaction->status }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                Tidak ada transaksi terbaru
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
              <a href="{{ route('manajergudang.stock.history') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
    Lihat Semua Transaksi
</a>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    // Stock Movement Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('stockMovementChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['categories']),
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($chartData['incoming']),
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['outgoing']),
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#6B7280'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6B7280',
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280'
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
