@extends('layouts.dashboard')

@section('title', 'Stock Opname')

@section('content')
<div class="container p-4 mx-auto sm:p-8">
    <div class="py-8">
        <div class="flex items-center mb-6 space-x-4">
            <a href="{{ route('manajergudang.dashboard') }}" class="flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700" title="Kembali"><i class="text-gray-600 fas fa-arrow-left dark:text-gray-300"></i></a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Stock Opname</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sesuaikan jumlah stok fisik dengan stok yang tercatat di sistem.</p>
            </div>
        </div>

        <form action="{{ route('manajergudang.stock.opname.store') }}" method="POST">
            @csrf
            <div class="overflow-hidden bg-white rounded-lg shadow-md dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-300">Produk</th>
                                <th class="w-40 px-6 py-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Stok Sistem</th>
                                <th class="w-48 px-6 py-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Stok Fisik (Real)</th>
                                <th class="w-32 px-6 py-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-300">Selisih</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:bg-slate-800 dark:divide-slate-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50" x-data="{ systemStock: {{ $product->current_stock ?? 0 }}, physicalStock: {{ old('products.'.$loop->index.'.physical_stock', $product->current_stock ?? 0) }} }">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" alt="{{ $product->name }}" class="object-cover w-10 h-10 rounded-md">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                                <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span x-text="systemStock" class="font-medium text-gray-700 dark:text-gray-300"></span> <span class="text-gray-500 dark:text-gray-400">{{ $product->unit }}</span>
                                        <input type="hidden" name="products[{{ $loop->index }}][system_stock]" :value="systemStock">
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <input type="number" name="products[{{ $loop->index }}][physical_stock]" x-model.number="physicalStock" placeholder="0" min="0" class="w-32 px-3 py-2 text-center border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 text-lg font-bold text-center whitespace-nowrap">
                                        <span x-show="physicalStock !== ''" x-cloak :class="{
                                                  'text-green-500': (physicalStock - systemStock) > 0,
                                                  'text-red-500': (physicalStock - systemStock) < 0,
                                                  'text-gray-500 dark:text-gray-400': (physicalStock - systemStock) == 0
                                              }" x-text="(physicalStock - systemStock) == 0 ? '0' : ((physicalStock - systemStock > 0 ? '+' : '') + (physicalStock - systemStock))">
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Tidak ada data produk untuk di-opname.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($products->hasPages())
                <div class="px-6 py-3 bg-gray-50 dark:bg-slate-700/50">
                    {{ $products->links() }}
                </div>
                @endif
                
                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-slate-700/50 border-t dark:border-slate-700">
                    <button type="submit" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700" onclick="return confirm('Yakin ingin menyimpan hasil stock opname? Stok akan disesuaikan secara permanen.')">
                        Simpan & Sesuaikan Stok
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection