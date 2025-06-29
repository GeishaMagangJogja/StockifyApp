@extends('layouts.dashboard')

@section('title', $title)

@section('content')
    <div class="mb-6">
        <div class="flex items-center mb-2 space-x-2 text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.suppliers.index') }}" class="hover:text-blue-600">Supplier</a>
            <span>/</span>
            <span>{{ $title }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
        <p class="text-gray-600 dark:text-gray-400">Isi form berikut untuk {{ strtolower($title) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-6">
            <form action="{{ $action }}" method="POST">
                @csrf
                @if(isset($supplier))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Supplier <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Contact Person <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('address') border-red-500 @enderror">{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-8 space-x-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 text-gray-700 transition duration-150 ease-in-out border border-gray-300 rounded-lg dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i class="mr-2 fas fa-times"></i>Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-white transition duration-150 ease-in-out bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="mr-2 fas fa-save"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
