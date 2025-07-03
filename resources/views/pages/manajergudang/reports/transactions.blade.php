@extends('layouts.dashboard')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="p-4 sm:p-8">
    {{-- Header --}}
    <div class="relative p-6 mb-8 overflow-hidden bg-gradient-to-r from-indigo-600/10 to-pink-600/10 rounded-2xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="mr-3 text-indigo-500 fas fa-exchange-alt"></i>Laporan Riwayat Transaksi</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Lacak semua pergerakan barang yang masuk dan keluar dari gudang.</p>
    </div>

    {{-- Filter Panel --}}
    <div class="p-6 mb-6 bg-white rounded-xl shadow-lg dark:bg-slate-800">
        <form action="{{ route('manajergudang.reports.transactions') }}" method="GET">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="w-full px-4 py-2 border rounded-lg lg:col-span-2 dark:bg-slate-700 dark:border-gray-600">
                <select name="type" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600"><option value="">Semua Tipe</option><option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option><option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option></select>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600">
            </div>
            <div class="flex items-center justify-end gap-2 pt-4 mt-4 border-t dark:border-slate-700">
                <a href="{{ route('manajergudang.reports.transactions') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">Reset</a>
                <button type="submit" class="flex items-center justify-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700"><i class="mr-2 fas fa-filter"></i>Filter</button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden bg-white rounded-xl shadow-lg dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700/50"><tr><th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Detail</th><th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">Jumlah</th></tr></thead>
                <tbody class="divide-y dark:divide-slate-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full {{ $transaction->type == 'Masuk' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                        <i class="text-xl {{ $transaction->type == 'Masuk' ? 'text-green-500 fas fa-arrow-down' : 'text-red-500 fas fa-arrow-up' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->product->name ?? 'Produk Dihapus' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span>{{ $transaction->date->format('d M Y, H:i') }}</span>
                                            <span class="mx-1">•</span>
                                            <span>oleh {{ $transaction->user->name ?? 'Sistem' }}</span>
                                        </p>
                                        @if($transaction->notes)<p class="mt-1 text-xs italic text-gray-400 dark:text-gray-500">"{{ $transaction->notes }}"</p>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xl font-bold {{ $transaction->type == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'Masuk' ? '+' : '-' }} {{ number_format($transaction->quantity) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-6 py-16 text-center text-gray-400"><div class="flex flex-col items-center"><i class="mb-4 text-5xl fas fa-exchange-alt opacity-50"></i><p class="text-lg">Tidak ada transaksi ditemukan.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())<div class="p-4 border-t dark:border-slate-700">{{ $transactions->appends(request()->query())->links() }}</div>@endif
    </div>
</div>
@endsection