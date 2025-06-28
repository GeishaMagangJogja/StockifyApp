@extends('layouts.dashboardstaff')

@section('title', 'Daftar Barang Keluar')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Riwayat & Daftar Barang Keluar
        </h2>
    </div>

    {{-- Menampilkan pesan sukses setelah konfirmasi --}}
    @if (session('success'))
        <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-green-500 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Container untuk Tabel --}}
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Catatan/Tujuan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($transactions as $transaction)
                        <tr class="text-gray-700 dark:text-gray-400">
                            {{-- Kolom Produk --}}
                            <td class="px-4 py-3">
                                <p class="font-semibold text-sm">{{ optional($transaction->product)->name ?? 'Produk Dihapus' }}</p>
                            </td>
                            {{-- Kolom Jumlah --}}
                            <td class="px-4 py-3 text-sm">{{ $transaction->quantity }}</td>
                            {{-- Kolom Catatan --}}
                            <td class="px-4 py-3 text-sm">{{ $transaction->notes ?? '-' }}</td>
                            {{-- Kolom Status --}}
                            <td class="px-4 py-3 text-xs">
                                @php
                                    $statusClass = 'text-gray-700 bg-gray-100'; // Default
                                    if ($transaction->status == 'pending') {
                                        $statusClass = 'text-orange-700 bg-orange-100 dark:text-white dark:bg-orange-600';
                                    } elseif (in_array($transaction->status, ['completed', 'dikeluarkan'])) {
                                        $statusClass = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                                    } elseif ($transaction->status == 'rejected') {
                                        $statusClass = 'text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-700';
                                    }
                                @endphp
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $statusClass }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            {{-- Kolom Tanggal --}}
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                            {{-- Kolom Aksi --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    @if($transaction->status == 'pending')
                                        <a href="{{ route('staff.stock.outgoing.prepare', $transaction) }}" class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Prepare">
                                            Siapkan
                                        </a>
                                    @else
                                        <button class="flex items-center justify-between px-3 py-1 text-sm font-medium leading-5 text-gray-400 bg-gray-200 rounded-md cursor-not-allowed" disabled>
                                            Detail
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data transaksi barang keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Bagian untuk link pagination --}}
        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
             <span class="flex items-center col-span-3">
                Menampilkan {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} data
            </span>
            <span class="col-span-2"></span>
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                {{ $transactions->links() }}
            </span>
        </div>
    </div>
@endsection