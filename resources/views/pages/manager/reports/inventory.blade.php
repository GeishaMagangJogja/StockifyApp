@extends('layouts.manager')

@section('title', 'Laporan Inventaris')
@section('header', 'Laporan Inventaris')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Laporan Inventaris
                </h3>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('manager.reports.export') }}?type=inventory" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                        Export PDF
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="bg-blue-50 rounded-lg p-4 dark:bg-blue-900 dark:bg-opacity-30">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Total Kategori
                    </h4>
                    <p class="mt-1 text-2xl font-semibold text-blue-600 dark:text-blue-100">
                        {{ $categories->count() }}
                    </p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 dark:bg-yellow-900 dark:bg-opacity-30">
                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        Stok Rendah
                    </h4>
                    <p class="mt-1 text-2xl font-semibold text-yellow-600 dark:text-yellow-100">
                        {{ $lowStockProducts }}
                    </p>
                </div>
                <div class="bg-red-50 rounded-lg p-4 dark:bg-red-900 dark:bg-opacity-30">
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                        Stok Habis
                    </h4>
                    <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-100">
                        {{ $outOfStockProducts }}
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                    Produk per Kategori
                </h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($categories as $category)
                    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="p-4">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h5>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $category->products_count }} produk
                            </p>
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $category->products_count / max($categories->max('products_count'), 1) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
