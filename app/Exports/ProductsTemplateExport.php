<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function collection()
    {
        // Contoh baris data (bisa dikosongkan jika tidak ingin ada contoh)
        return collect([
            [
                'PRD-001',
                'Produk Contoh',
                'Elektronik',
                'Supplier A',
                'Deskripsi produk contoh',
                100000,
                150000,
                50,
                10,
                'pcs'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'sku',
            'name',
            'category_name',
            'supplier_name',
            'description',
            'purchase_price',
            'selling_price',
            'current_stock',
            'min_stock',
            'unit',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Add data validation for category and supplier
        $lastRow = $sheet->getHighestRow();

        // Category validation
        $categories = Category::pluck('name')->toArray();
        $categoryValidation = $sheet->getDataValidation('C2:C' . ($lastRow + 100));
        $categoryValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $categoryValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $categoryValidation->setAllowBlank(false);
        $categoryValidation->setShowInputMessage(true);
        $categoryValidation->setShowErrorMessage(true);
        $categoryValidation->setShowDropDown(true);
        $categoryValidation->setErrorTitle('Input error');
        $categoryValidation->setError('Value is not in list');
        $categoryValidation->setPromptTitle('Pilih Kategori');
        $categoryValidation->setPrompt('Pilih kategori dari daftar yang tersedia');
        $categoryValidation->setFormula1('"' . implode(',', $categories) . '"');

        // Supplier validation (optional)
        $suppliers = Supplier::pluck('name')->toArray();
        $supplierValidation = $sheet->getDataValidation('D2:D' . ($lastRow + 100));
        $supplierValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $supplierValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $supplierValidation->setAllowBlank(true);
        $supplierValidation->setShowInputMessage(true);
        $supplierValidation->setShowErrorMessage(true);
        $supplierValidation->setShowDropDown(true);
        $supplierValidation->setErrorTitle('Input error');
        $supplierValidation->setError('Value is not in list');
        $supplierValidation->setPromptTitle('Pilih Supplier');
        $supplierValidation->setPrompt('Pilih supplier dari daftar yang tersedia');
        $supplierValidation->setFormula1('"' . implode(',', $suppliers) . '"');

        // Add example data
        $sheet->fromArray([
            [
                'PRD-001',
                'Produk Contoh',
                $categories[0] ?? 'Elektronik',
                $suppliers[0] ?? 'Supplier A',
                'Deskripsi produk contoh',
                100000,
                150000,
                50,
                10,
                'pcs'
            ]
        ], null, 'A2', true);

        // Add notes sheet
        $this->addNotesSheet($sheet->getParent());

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Template Import';
    }

    protected function addNotesSheet($spreadsheet)
    {
        $notesSheet = $spreadsheet->createSheet();
        $notesSheet->setTitle('Petunjuk');

        $notes = [
            ['PETUNJUK PENGISIAN TEMPLATE IMPORT PRODUK'],
            [],
            ['Kolom Wajib Diisi:'],
            ['- sku: Kode unik produk (unik, tidak boleh duplikat)'],
            ['- name: Nama produk'],
            ['- category_name: Pilih dari daftar kategori yang tersedia'],
            ['- purchase_price: Harga beli (angka tanpa tanda titik/koma)'],
            ['- selling_price: Harga jual (harus ≥ harga beli)'],
            ['- current_stock: Stok saat ini (angka)'],
            ['- min_stock: Stok minimum (angka)'],
            ['- unit: Satuan (pcs, kg, liter, dll)'],
            [],
            ['Kolom Opsional:'],
            ['- supplier_name: Pilih dari daftar supplier'],
            ['- description: Deskripsi produk'],
            [],
            ['CONTOH FORMAT HARGA:'],
            ['100000 (seratus ribu)'],
            ['1500000 (satu juta lima ratus ribu)'],
        ];

        $notesSheet->fromArray($notes, null, 'A1');
        $notesSheet->getStyle('A1')->getFont()->setBold(true);
        $notesSheet->getColumnDimension('A')->setAutoSize(true);
    }
}
