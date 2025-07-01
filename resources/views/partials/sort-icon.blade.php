@php
    $currentSort = request('sort', 'name_asc');
    $sortFields = [
        'name' => ['name_asc', 'name_desc'],
        'price' => ['price_asc', 'price_desc'],
        'stock' => ['stock_asc', 'stock_desc']
    ];

    $isActive = in_array($currentSort, $sortFields[$field] ?? []);
    $isAsc = in_array($currentSort, ['name_asc', 'price_asc', 'stock_asc']);
@endphp

@if($isActive)
    <i class="ml-1 fas fa-sort-{{ $isAsc ? 'up' : 'down' }}"></i>
@else
    <i class="ml-1 fas fa-sort"></i>
@endif
