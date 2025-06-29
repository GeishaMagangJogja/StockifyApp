@extends('layouts.dashboard')

@section('title', 'Stock Opname')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Stock Opname</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Sesuaikan jumlah stok fisik dengan stok yang tercatat di sistem.</p>
        </div>

        <div class="mt-8">
            <form action="{{ route('manajergudang.stock.opname.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok Sistem</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok Fisik (Real)</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Selisih</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y dark:divide-slate-700">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50" x-data="{ physicalStock: '', systemStock: {{ $product->current_stock ?? 0 }} }">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->code }}</p>
                                            <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <span x-text="systemStock" class="font-medium"></span> {{ $product->unit }}
                                            <input type="hidden" name="products[{{ $loop->index }}][system_stock]" :value="systemStock">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="products[{{ $loop->index }}][physical_stock]" x-model.number="physicalStock" placeholder="0" min="0" 
                                                   class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                            <span x-show="physicalStock !== ''" x-cloak
                                                  :class="{
                                                      'text-green-600': (physicalStock - systemStock) > 0,
                                                      'text-red-600': (physicalStock - systemStock) < 0,
                                                      'text-gray-500 dark:text-gray-400': (physicalStock - systemStock) == 0
                                                  }"
                                                  x-text="(physicalStock - systemStock > 0 ? '+' : '') + (physicalStock - systemStock)">
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Tidak ada data produk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/50 border-t dark:border-slate-700 flex justify-between items-center">
                        <div>{{ $products->links() }}</div>
                        <div>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                                    onclick="return confirm('Yakin ingin menyimpan hasil stock opname? Stok akan disesuaikan secara permanen.')">
                                Simpan & Sesuaikan Stok
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection