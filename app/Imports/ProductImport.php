<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Abaikan baris jika SKU atau nama produk kosong (atau semua kolom penting kosong)
        if (
            empty($row['sku']) &&
            empty($row['name']) &&
            empty($row['category_name']) &&
            empty($row['purchase_price']) &&
            empty($row['selling_price'])
        ) {
            return null;
        }

        $category = Category::where('name', $row['category_name'] ?? '')->first();
        $supplier = null;
        if (!empty($row['supplier_name'])) {
            $supplier = Supplier::where('name', $row['supplier_name'])->first();
        }

        $product = Product::updateOrCreate(
            ['sku' => $row['sku'] ?? ''],
            [
                'category_id'     => $category ? $category->id : null,
                'supplier_id'     => $supplier ? $supplier->id : null,
                'name'            => $row['name'] ?? '',
                'description'     => $row['description'] ?? '',
                'purchase_price'  => $row['purchase_price'] ?? 0,
                'selling_price'   => $row['selling_price'] ?? 0,
                'current_stock'   => $row['current_stock'] ?? 0,
                'min_stock'       => $row['min_stock'] ?? 0,
                'unit'            => $row['unit'] ?? 'pcs',
            ]
        );

        // Jika produk baru dan current_stock > 0, buat transaksi stok awal
        if ($product->wasRecentlyCreated && ($row['current_stock'] ?? 0) > 0) {
            StockTransaction::create([
                'product_id' => $product->id,
                'user_id' => auth()->id() ?? 1, // fallback ke user id 1 jika import via CLI
                'type' => 'Masuk',
                'quantity' => $row['current_stock'],
                'notes' => 'Stok awal dari import',
                'date' => now(),
            ]);
        }

        return $product;
    }
}
