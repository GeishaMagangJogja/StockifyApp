@extends('layouts.dashboard')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Produk</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.create') }}"
                   class="px-4 py-2 text-white transition duration-150 bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-plus"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>

    @include('partials.alert')

    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-slate-800">
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/SKU/deskripsi..."
                           class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <select name="category" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="stock_status" class="w-full px-4 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Stok Normal</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'stock_status']))
                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'name_asc' ? 'name_desc' : 'name_asc']) }}">
                                Produk @include('partials.sort-icon', ['field' => 'name'])
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            Supplier
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'price_asc' ? 'price_desc' : 'price_asc']) }}">
                                Harga @include('partials.sort-icon', ['field' => 'price'])
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') == 'stock_asc' ? 'stock_desc' : 'stock_asc']) }}">
                                Stok @include('partials.sort-icon', ['field' => 'stock'])
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-300">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-800 dark:divide-gray-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <img class="object-cover w-10 h-10 rounded"
                                         src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}"
                                         alt="{{ $product->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="hover:underline">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                            {{ $product->supplier->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Beli: Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Min: {{ number_format($product->min_stock, 0) }} {{ $product->unit }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->current_stock <= 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                                    Habis
                                </span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900/50 dark:text-yellow-300">
                                    Rendah
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">
                                    Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                   class="p-2 text-blue-500 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="p-2 text-yellow-500 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/50"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-red-500 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="mb-2 text-4xl fas fa-box-open"></i>
                            <p class="text-lg">Tidak ada produk ditemukan</p>
                            @if(request()->anyFilled(['search', 'category', 'stock_status']))
                                <a href="{{ route('admin.products.index') }}" class="mt-2 text-blue-500 hover:underline">Reset pencarian</a>
                            @else
                                <a href="{{ route('admin.products.create') }}" class="mt-2 text-blue-500 hover:underline">Tambahkan produk pertama</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
@endsection
