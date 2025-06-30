@extends('layouts.dashboard')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        {{-- Header Halaman --}}
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Laporan Riwayat Transaksi</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Lacak semua pergerakan barang di gudang.</p>
            </div>
            <div class="flex items-center space-x-2">
                {{-- Tombol Ekspor --}}
                <button class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-md flex items-center">
                    <i class="fas fa-file-excel mr-2"></i>Ekspor
                </button>
            </div>
        </div>

        {{-- Panel Filter --}}
        <div class="mb-6 p-4 bg-white dark:bg-slate-800 rounded-lg shadow">
            <form action="{{ route('manajergudang.reports.transactions') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Filter Tipe --}}
                    <div class="lg:col-span-1">
                        <select name="type" id="type" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Tipe</option>
                            <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                    </div>
                    {{-- Filter Tanggal Mulai --}}
                    <div class="lg:col-span-1">
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" title="Dari Tanggal">
                    </div>
                    {{-- Filter Tanggal Akhir --}}
                    <div class="lg:col-span-1">
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" title="Sampai Tanggal">
                    </div>
                    {{-- Tombol Aksi Filter --}}
                    <div class="lg:col-span-2 flex items-center justify-end gap-2">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('manajergudang.reports.transactions') }}" class="w-full sm:w-auto px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 flex items-center justify-center">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Laporan Transaksi --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Detail Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Detail Transaksi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                        @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            {{-- Kolom Detail Produk --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-lg object-cover mr-4" src="{{ $transaction->product->image ? asset('storage/' . $transaction->product->image) : 'https://ui-avatars.com/api/?name='.urlencode($transaction->product->name ?? 'P').'&background=random' }}" alt="{{ $transaction->product->name ?? 'Produk' }}">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $transaction->product->name ?? 'Produk Telah Dihapus' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Supplier: {{ $transaction->supplier->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            {{-- Kolom Detail Transaksi --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    @if($transaction->type == 'Masuk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Barang Masuk</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Barang Keluar</span>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }} oleh {{ $transaction->user->name ?? 'Sistem' }}</p>
                                </div>
                            </td>
                            {{-- Kolom Jumlah --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'Masuk' ? '+' : '-' }} {{ number_format($transaction->quantity) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-exchange-alt text-4xl mb-3"></i>
                                    <p>Tidak ada transaksi yang cocok dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="p-4 border-t dark:border-slate-700">{{ $transactions->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection