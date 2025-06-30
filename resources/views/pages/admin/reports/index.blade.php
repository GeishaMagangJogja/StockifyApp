@extends('layouts.dashboard')

@section('title', 'Dashboard Laporan')

@section('content')
<div class="grid gap-8">
    <div>
        @include('pages.admin.reports.partials.stock', ['products' => $products, 'categories' => $categories])
    </div>
    <div>
        @include('pages.admin.reports.partials.transactions', ['transactions' => $transactions])
    </div>
</div>
@endsection
