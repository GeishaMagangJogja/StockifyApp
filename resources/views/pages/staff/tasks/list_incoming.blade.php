@extends('layouts.dashboardstaff')

@section('title', 'Daftar Barang Masuk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Riwayat & Daftar Barang Masuk
        </h2>
    </div>

    @if (session('success'))
        <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-green-500 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($transactions as $transaction)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <p class="font-semibold">{{ optional($transaction->product)->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $transaction->quantity }}</td>
                            <td class="px-4 py-3 text-sm">{{ optional($transaction->supplier)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full @if($transaction->status == 'pending') text-orange-700 bg-orange-100 @else text-green-700 bg-green-100 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                @if($transaction->status == 'pending')
                                    <a href="{{ route('staff.stock.incoming.confirm', $transaction) }}" class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md">Proses</a>
                                @else
                                    <span class="text-sm text-gray-500">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">Tidak ada riwayat barang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection