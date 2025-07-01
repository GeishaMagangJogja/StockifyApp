@extends('layouts.dashboard')

@section('title', $reportTitle)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ $reportTitle }}
        </h2>
        {{-- Nanti bisa ditambahkan tombol Cetak/Ekspor PDF di sini --}}
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Diproses Oleh</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($transactions as $transaction)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ optional($transaction->product)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $transaction->quantity }}</td>
                            <td class="px-4 py-3 text-sm">{{ optional($transaction->supplier)->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $transaction->status == 'completed' || $transaction->status == 'diterima' ? 'text-green-700 bg-green-100 dark:text-green-100 dark:bg-green-700' : 'text-gray-700 bg-gray-100' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                             <td class="px-4 py-3 text-sm">{{ optional($transaction->user)->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">Tidak ada data untuk dilaporkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection