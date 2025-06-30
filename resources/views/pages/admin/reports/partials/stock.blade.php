<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Laporan Stok Barang</h2>

    <form method="GET" class="flex flex-wrap items-end gap-4 mb-4">
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Produk</label>
            <select name="category_id" class="w-52 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm">
                <option value="">-- Semua Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
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
                    <th class="px-4 py-3 border-b dark:border-gray-600">Kategori</th>
                    <th class="px-4 py-3 border-b dark:border-gray-600">Jumlah Transaksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-100">
                @forelse($products as $product)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $product->stock_transactions_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-gray-500 dark:text-gray-400">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
