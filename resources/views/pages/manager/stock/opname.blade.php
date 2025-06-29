@extends('layouts.manager')

@section('title', 'Stock Opname')
@section('header', 'Stock Opname')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Stock Opname
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Verifikasi stok fisik dengan data sistem
            </p>

            <div class="mt-6">
                <form action="{{ route('manajergudang.stock.opname') }}" method="GET" class="mb-4">
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                        <input type="text" name="search" placeholder="Cari produk..."
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               value="{{ request('search') }}">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Produk
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Stok Sistem
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Stok Fisik
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Selisih
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($product->image)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                        </div>
                                        @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center dark:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->sku }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($product->stock) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form id="opnameForm{{ $product->id }}" action="{{ route('manajergudang.stock.opname.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="number" name="actual_stock" min="0"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                               placeholder="0" value="{{ $product->stock }}">
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <span id="difference{{ $product->id }}">0</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="submit" form="opnameForm{{ $product->id }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Simpan
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada produk yang ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate difference between system stock and physical count
        document.querySelectorAll('input[name="actual_stock"]').forEach(input => {
            input.addEventListener('input', function() {
                const form = this.closest('form');
                const productId = form.querySelector('input[name="product_id"]').value;
                const systemStock = parseInt(this.defaultValue);
                const physicalCount = parseInt(this.value) || 0;
                const difference = physicalCount - systemStock;

                document.getElementById(`difference${productId}`).textContent = difference;

                if (difference > 0) {
                    document.getElementById(`difference${productId}`).className = 'text-green-600 dark:text-green-400';
                } else if (difference < 0) {
                    document.getElementById(`difference${productId}`).className = 'text-red-600 dark:text-red-400';
                } else {
                    document.getElementById(`difference${productId}`).className = 'text-gray-500 dark:text-gray-300';
                }
            });
        });
    });
</script>
@endpush
@endsection
