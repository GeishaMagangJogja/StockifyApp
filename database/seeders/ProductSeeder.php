<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop Pro 14 inch', 'sku' => 'LP-14-2024', 'category_id' => 1, 'supplier_id' => 1,
            'purchase_price' => 15000000, 'selling_price' => 17500000, 'stock' => 10, 'minimum_stock' => 5,
        ]);
        Product::create([
            'name' => 'T-Shirt Katun Combed 30s', 'sku' => 'TS-KC-30S-BLK', 'category_id' => 2, 'supplier_id' => 2,
            'purchase_price' => 55000, 'selling_price' => 80000, 'stock' => 100, 'minimum_stock' => 20,
        ]);
    }
}