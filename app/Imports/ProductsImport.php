<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $rows = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Skip jika data kosong
                if (empty($row['sku']) && empty($row['name'])) {
                    continue;
                }

                ++$this->rows;

                // Cari atau buat kategori
                $category = Category::firstOrCreate([
                    'name' => $row['category_name']
                ], [
                    'description' => 'Imported from Excel'
                ]);

                // Cari supplier jika ada
                $supplier = null;
                if (!empty($row['supplier_name'])) {
                    $supplier = Supplier::firstOrCreate([
                        'name' => $row['supplier_name']
                    ], [
                        'contact_person' => 'Imported',
                        'phone' => '0000000000',
                        'email' => 'imported@example.com'
                    ]);
                }

                // Data produk
                $productData = [
                    'sku' => $row['sku'],
                    'name' => $row['name'],
                    'category_id' => $category->id,
                    'supplier_id' => $supplier ? $supplier->id : null,
                    'description' => $row['description'] ?? null,
                    'purchase_price' => $row['purchase_price'] ?? 0,
                    'selling_price' => $row['selling_price'] ?? 0,
                    'current_stock' => $row['current_stock'] ?? 0,
                    'min_stock' => $row['min_stock'] ?? 0,
                    'unit' => $row['unit'] ?? 'pcs',
                ];

                // Update atau create produk
                Product::updateOrCreate(
                    ['sku' => $row['sku']],
                    $productData
                );

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$this->rows}: " . $e->getMessage();
                continue;
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.sku' => ['required', 'string', 'max:100'],
            '*.name' => ['required', 'string', 'max:255'],
            '*.category_name' => ['required', 'string'],
            '*.supplier_name' => ['nullable', 'string'],
            '*.purchase_price' => ['required', 'numeric', 'min:0'],
            '*.selling_price' => ['required', 'numeric', 'min:0'],
            '*.current_stock' => ['required', 'integer', 'min:0'],
            '*.min_stock' => ['required', 'integer', 'min:0'],
            '*.unit' => ['required', 'string', 'max:20'],
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
