@extends('layouts.dashboard')

@section('title', 'Tambah Supplier Baru')

@section('content')
<div class="container mx-auto px-4 sm:px-8 max-w-4xl">
    <div class="py-8">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.suppliers.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Supplier Baru</h1>
            </div>
        </div>

        <div class="mt-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                <form action="{{ route('admin.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Supplier <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: PT Sumber Jaya" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@supplier.com" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08123456789" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat (Opsional)</label>
                            <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:border-gray-600 dark:text-white" placeholder="Jalan, Kota, Provinsi, Kode Pos">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/50 text-right">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md">
                            Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection