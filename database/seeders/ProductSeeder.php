<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'supplier_id' => 1,
                'name' => 'Laptop Acer Swift 3',
                'sku' => 'ACER-SWIFT-3',
                'description' => 'Ultrabook 14 inci, Ryzen 5, SSD 512GB',
                'purchase_price' => 8500000,
                'selling_price' => 9500000,
                'image' => 'images/products/acer-swift-3.jpg',
                'minimum_stock' => 5,
            ],
            [
                'category_id' => 1,
                'supplier_id' => 2,
                'name' => 'Asus Vivobook 15',
                'sku' => 'ASUS-VBOOK-15',
                'description' => 'Laptop 15.6 inci, Core i5, HDD 1TB',
                'purchase_price' => 7200000,
                'selling_price' => 8200000,
                'image' => 'images/products/asus-vivobook.jpg',
                'minimum_stock' => 3,
            ],
            [
                'category_id' => 2,
                'supplier_id' => 1,
                'name' => 'HP LaserJet Pro M12',
                'sku' => 'HP-LASER-M12',
                'description' => 'Printer laser hitam putih, USB only',
                'purchase_price' => 1200000,
                'selling_price' => 1450000,
                'image' => 'images/products/hp-laser-m12.jpg',
                'minimum_stock' => 2,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Produk berhasil disimpan.');
    }
}
