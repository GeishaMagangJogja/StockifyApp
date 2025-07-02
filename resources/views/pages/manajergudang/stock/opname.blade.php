@extends('layouts.dashboard')

@section('title', 'Stock Opname')

@section('content')
<div class="container p-4 mx-auto sm:p-8" x-data="{ showFilter: false }">
    <div class="py-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50">
                <i class="text-xl text-blue-600 fas fa-tasks dark:text-blue-400"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Stock Opname</h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Sesuaikan jumlah stok fisik dengan stok sistem.</p>
            </div>
        </div>
        <form id="filterForm" action="{{ route('manajergudang.stock.opname') }}" method="GET" class="mb-6">
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><i class="text-gray-400 fas fa-search"></i></div>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk berdasarkan nama atau SKU..." class="w-full px-4 py-3 pl-12 pr-4 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="button" @click="showFilter = !showFilter" class="flex items-center justify-center px-4 py-3 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"><i class="mr-2 fas fa-filter"></i><span>Filter Lanjutan</span></button>
            </div>
            <div x-show="showFilter" x-collapse class="mt-4"><div class="p-4 bg-gray-50 border rounded-lg dark:bg-slate-800 dark:border-slate-700"><label for="category_id" class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Filter berdasarkan Kategori</label><div class="flex gap-4"><select name="category_id" id="category_id" class="flex-1 w-full px-4 py-3 text-sm border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white"><option value="">Semua Kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>@endforeach</select><button type="submit" class="px-6 py-3 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Terapkan</button>@if(request('search') || request('category_id'))<a href="{{ route('manajergudang.stock.opname') }}" class="p-3 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300" title="Reset Filter"><i class="fas fa-undo"></i></a>@endif</div></div></div>
        </form>

        <form action="{{ route('manajergudang.stock.opname.store') }}" method="POST">
            @csrf
            <div class="overflow-hidden bg-white rounded-xl shadow-lg dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed">
                        <thead class="bg-gray-50 dark:bg-slate-700/50"><tr><th class="w-2/5 px-4 py-4 text-xs font-semibold tracking-wider text-left text-gray-400 uppercase"><i class="mr-2 fas fa-box"></i>Produk</th><th class="w-1/4 px-4 py-4 text-xs font-semibold tracking-wider text-left text-gray-400 uppercase"><i class="mr-2 fas fa-truck"></i>Supplier (Opsional)</th><th class="w-1/6 px-4 py-4 text-xs font-semibold tracking-wider text-center text-gray-400 uppercase"><i class="mr-2 fas fa-clipboard-check"></i>Stok Sistem</th><th class="w-1/6 px-4 py-4 text-xs font-semibold tracking-wider text-center text-gray-400 uppercase"><i class="mr-2 fas fa-ruler-combined"></i>Stok Fisik</th><th class="w-1/6 px-4 py-4 text-xs font-semibold tracking-wider text-center text-gray-400 uppercase"><i class="mr-2 fas fa-arrows-alt-h"></i>Selisih</th></tr></thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="border-b dark:border-slate-700 last:border-b-0" x-data="{ systemStock: {{ $product->current_stock ?? 0 }}, physicalStock: {{ old('products.'.$loop->index.'.physical_stock', $product->current_stock ?? 0) }} }">
                                    <td class="p-4"><div class="flex items-center space-x-4"><img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name) }}" alt="{{ $product->name }}" class="object-cover w-12 h-12 rounded-lg"><div><p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p><p class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p><input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}"></div></div></td>
                                    <td class="p-4"><select name="products[{{ $loop->index }}][supplier_id]" class="w-full px-4 py-2 text-sm border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white"><option value="">-- Tanpa Supplier --</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>@endforeach</select></td>
                                    <td class="p-4 text-center"><span x-text="systemStock" class="text-xl font-medium text-gray-700 dark:text-gray-300"></span><span class="ml-1 text-sm text-gray-500">{{ $product->unit }}</span><input type="hidden" name="products[{{ $loop->index }}][system_stock]" :value="systemStock"></td>
                                    <td class="p-4 text-center"><input type="number" name="products[{{ $loop->index }}][physical_stock]" x-model.number="physicalStock" min="0" class="w-24 px-3 py-2 text-xl font-bold text-center text-gray-900 bg-white border-2 border-gray-300 rounded-lg dark:bg-slate-900 dark:text-white dark:border-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></td>
                                    <td class="p-4 text-3xl font-bold text-center"><span x-show="physicalStock !== ''" x-cloak :class="{'text-green-500': (physicalStock - systemStock) > 0, 'text-red-500': (physicalStock - systemStock) < 0, 'text-gray-400': (physicalStock - systemStock) == 0}" x-text="(physicalStock - systemStock) == 0 ? '0' : ((physicalStock - systemStock > 0 ? '+' : '') + (physicalStock - systemStock))"></span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400"><div class="flex flex-col items-center"><i class="mb-4 text-5xl fas fa-box-open opacity-50"></i><p class="text-lg">Produk tidak ditemukan</p><p class="text-sm">Coba reset filter atau gunakan kata kunci lain.</p></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())<div class="p-4 border-t dark:border-slate-700">{{ $products->appends(request()->query())->links() }}</div>@endif
                <div class="flex items-center justify-end p-6 border-t dark:border-slate-700"><button type="submit" class="px-8 py-3 font-semibold text-white bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700" onclick="return confirm('Apakah Anda yakin ingin menyimpan hasil stock opname? Stok akan disesuaikan secara permanen.')"><i class="mr-2 fas fa-save"></i>Simpan & Sesuaikan Stok</button></div>
            </div>
        </form>
    </div>
</div>
@endsection