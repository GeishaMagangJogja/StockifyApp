@extends('layouts.manager')

@section('title', 'Riwayat Stok')
@section('header', 'Riwayat Stok')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Riwayat Perpindahan Stok
            </h3>

            <div class="mt-6">
                <form action="{{ route('manajergudang.stock.history') }}" method="GET" class="mb-4">
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                        <select name="type" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Jenis</option>
                            <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                        <select name="product_id" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Produk</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                        <input type="date" name="date_from" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ request('date_to') }}">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                            Filter
                        </button>
                    </div>
                </form>

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
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Catatan
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Pengguna
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse ($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
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
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
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
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                    {{ $transaction->user->name }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada riwayat transaksi
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
</div>
@endsection
