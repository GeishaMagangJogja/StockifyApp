@extends('layouts.dashboard')

@section('title', 'Laporan Sistem')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($systemData as $label => $value)
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 transition hover:scale-105 hover:shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ ucwords(str_replace('_', ' ', $label)) }}</div>
            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 text-xs font-bold">
                {{ substr($label, 0, 1) }}
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $value }}</div>
        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Total {{ str_replace('_', ' ', $label) }}
        </div>
    </div>
    @endforeach
</div>
@endsection
