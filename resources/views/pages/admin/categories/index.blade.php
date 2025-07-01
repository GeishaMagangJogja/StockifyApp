@extends('layouts.dashboard')

@section('title', 'Kategori Produk')

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <span>Kategori</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kategori Produk</h1>
                <p class="text-gray-600 dark:text-gray-400">Kelola kategori produk dalam sistem gudang</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
               class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="mr-2 fas fa-plus"></i>Tambah Kategori
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg dark:bg-blue-900">
                        <i class="text-blue-600 fas fa-tags dark:text-blue-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kategori</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $categories->total() }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg dark:bg-green-900">
                        <i class="text-green-600 fas fa-cube dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $categories->sum('products_count') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                        <i class="text-yellow-600 fas fa-chart-bar dark:text-yellow-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Produk</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $categories->count() > 0 ? round($categories->sum('products_count') / $categories->count(), 1) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-6">
           <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kategori</h2>
    <div class="flex space-x-3">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center space-x-2">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama..."
                       class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="text-gray-400 fas fa-search"></i>
                </div>
            </div>
            <button type="submit"
                    class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>
</div>

            @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Kategori
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Deskripsi
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Jumlah Produk
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Dibuat
                                </th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg dark:bg-blue-900">
                                                    <i class="text-blue-600 fas fa-tag dark:text-blue-400"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $category->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    ID: #{{ $category->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ Str::limit($category->description ?? 'Tidak ada deskripsi', 50) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                            {{ $category->products_count }} produk
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            @else
                <div class="py-12 text-center">
                    <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full dark:bg-gray-700">
                        <i class="text-3xl text-gray-400 fas fa-tags"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Belum ada kategori</h3>
                    <p class="mb-6 text-gray-500 dark:text-gray-400">Mulai dengan menambahkan kategori produk pertama Anda.</p>
                    <a href="{{ route('admin.categories.create') }}"
                       class="inline-flex items-center px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-plus"></i>Tambah Kategori
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
