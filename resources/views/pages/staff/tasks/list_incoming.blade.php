@extends('layouts.dashboard')

@section('title', 'Daftar Barang Masuk')

@section('content')
    {{-- Header dengan Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-tachometer-alt w-4 h-4 mr-2.5"></i>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right w-3 h-3 text-gray-400 mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Daftar Barang Masuk</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="px-4 py-3 mb-4 text-sm font-medium text-white bg-green-500 rounded-lg" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card Utama --}}
    <div class="w-full p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        {{-- Header Card dengan Filter --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                Riwayat & Daftar Barang Masuk
            </h2>
            {{-- Tombol bisa ditambahkan di sini --}}
        </div>

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('staff.stock.incoming.list') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                {{-- Kolom Pencarian --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <label for="search" class="sr-only">Cari</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Cari produk berdasarkan nama atau SKU..."
                               value="{{ $search ?? '' }}">
                    </div>
                </div>

                {{-- Dropdown Filter Status --}}
                <div>
                    <label for="status" class="sr-only">Filter Status</label>
                    <select name="status" id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="semua" {{ ($selectedStatus ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ ($selectedStatus ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ ($selectedStatus ?? '') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ ($selectedStatus ?? '') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Tombol Aksi Filter --}}
                <div>
                    <button type="submit" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel Data --}}
        <div class="w-full overflow-hidden rounded-lg">
            <div class="w-full overflow-x-auto border-t dark:border-gray-700">
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
                                {{-- ... (Isi tabel sama seperti sebelumnya) ... --}}
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ optional($transaction->product)->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $transaction->quantity }}</td>
                                <td class="px-4 py-3 text-sm">{{ optional($transaction->supplier)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-xs">
                                    {{-- ... logika status ... --}}
                                </td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    @if($transaction->status == 'pending')
                                        <a href="{{ route('staff.stock.incoming.confirm', $transaction) }}" class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md">Proses</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center">Data tidak ditemukan. Coba ubah kata kunci pencarian atau filter Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">Menampilkan {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }}</span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    {{ $transactions->links() }}
                </span>
            </div>
        </div>
    </div>
@endsection