@extends('layouts.manager')

@section('title', 'Barang Keluar')
@section('header', 'Barang Keluar')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Daftar Barang Keluar
                </h3>
                <div class="mt-4 sm:mt-0">
                    <a href="#addOutgoingModal" data-modal-toggle="addOutgoingModal" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                        Tambah Barang Keluar
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <form action="{{ route('manajergudang.stock.out') }}" method="GET" class="mb-4">
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                        <select name="status" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Dikeluarkan" {{ request('status') == 'Dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <input type="date" name="date_from" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ request('date_to') }}">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                            Filter
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Produk
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Jumlah
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Catatan
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse ($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $transaction->date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $transaction->product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->product->sku }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($transaction->quantity) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->status === 'Dikeluarkan')
                                    <span class="px-2 py-1 text-xs font-semibold leading-4 text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">
                                        {{ $transaction->status }}
                                    </span>
                                    @elseif ($transaction->status === 'Pending')
                                    <span class="px-2 py-1 text-xs font-semibold leading-4 text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                        {{ $transaction->status }}
                                    </span>
                                    @else
                                    <span class="px-2 py-1 text-xs font-semibold leading-4 text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">
                                        {{ $transaction->status }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ $transaction->notes ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($transaction->status === 'Pending')
                                    <div class="flex space-x-2">
                                        <form action="{{ route('manajergudang.stock.approve', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('manajergudang.stock.reject', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi barang keluar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Outgoing Stock Modal -->
<div id="addOutgoingModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="addOutgoingModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Tambah Barang Keluar</h3>
                <form class="space-y-6" action="{{ route('manajergudang.stock.out.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="product_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produk</label>
                        <select id="product_id" name="product_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->name }} ({{ $product->sku }}) - Stok: {{ $product->stock }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" min="1" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan jumlah">
                        <p id="stockWarning" class="mt-1 text-sm text-red-600 dark:text-red-500 hidden">Jumlah melebihi stok yang tersedia!</p>
                    </div>
                    <div>
                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" name="date" id="date" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan catatan jika perlu"></textarea>
                    </div>
                    <button type="submit" id="submitBtn" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const stockWarning = document.getElementById('stockWarning');
        const submitBtn = document.getElementById('submitBtn');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const availableStock = selectedOption.getAttribute('data-stock');

            if (availableStock) {
                quantityInput.setAttribute('max', availableStock);
            }
        });

        quantityInput.addEventListener('input', function() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const availableStock = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
            const enteredQuantity = parseInt(this.value) || 0;

            if (enteredQuantity > availableStock) {
                stockWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                submitBtn.classList.add('bg-gray-400', 'dark:bg-gray-500', 'cursor-not-allowed');
            } else {
                stockWarning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.add('bg-blue-700', 'hover:bg-blue-800', 'dark:bg-blue-600', 'dark:hover:bg-blue-700');
                submitBtn.classList.remove('bg-gray-400', 'dark:bg-gray-500', 'cursor-not-allowed');
            }
        });
    });
</script>
@endpush
