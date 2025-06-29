@extends('layouts.manager')

@section('title', 'Laporan Transaksi')
@section('header', 'Laporan Transaksi Stok')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Laporan Transaksi Stok Bulan Ini
                </h3>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('manager.reports.export') }}?type=transactions" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                        Export PDF
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-blue-50 rounded-lg p-4 dark:bg-blue-900 dark:bg-opacity-30">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Total Barang Masuk
                    </h4>
                    <p class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-100">
                        {{ number_format($incoming) }}
                    </p>
                </div>
                <div class="bg-red-50 rounded-lg p-4 dark:bg-red-900 dark:bg-opacity-30">
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Total Barang Keluar
                    </h4>
                    <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-100">
                        {{ number_format($outgoing) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
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
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Catatan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($transactions as $transaction)
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
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                {{ $transaction->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                Tidak ada transaksi bulan ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
