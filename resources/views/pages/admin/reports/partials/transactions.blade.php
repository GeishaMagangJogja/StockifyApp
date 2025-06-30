<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Laporan Transaksi Barang</h2>

    <form method="GET" class="flex flex-wrap items-end gap-4 mb-4">
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Transaksi</label>
            <select name="type" class="w-48 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
                <option value="">-- Semua Jenis --</option>
                <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari</label>
            <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai</label>
            <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
        </div>

        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium shadow transition">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm table-auto border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Produk</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Jenis</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Jumlah</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Pengguna</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-100">
                @forelse($transactions as $trx)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $trx->product->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                {{ $trx->type == 'masuk' ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                {{ $trx->type == 'masuk' ? 'Barang Masuk' : 'Barang Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $trx->quantity }}</td>
                        <td class="px-4 py-3">{{ $trx->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $trx->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-gray-500 dark:text-gray-400">Tidak ada data transaksi ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
