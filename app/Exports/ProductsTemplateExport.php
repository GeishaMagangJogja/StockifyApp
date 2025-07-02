<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return collect([]); // Return empty collection for template
    }

public function headings(): array
{
    return [
        ['FORMAT IMPORT PRODUK'],
        ['Pastikan kolom berikut ada di file Excel Anda:'],
        [],
        [
            'sku',
            'name',
            'category_name',
            'supplier_name',
            'description',
            'purchase_price',
            'selling_price',
            'current_stock',
            'min_stock',
            'unit'
        ],
        [],
        ['CATEGORY YANG TERSEDIA:'],
        ...Category::pluck('name')->map(function($name) {
            return [$name];
        })->toArray(),
        [],
        ['SUPPLIER YANG TERSEDIA:'],
        ...Supplier::pluck('name')->map(function($name) {
            return [$name];
        })->toArray()
    ];
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
