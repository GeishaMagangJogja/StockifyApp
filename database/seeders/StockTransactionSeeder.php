<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Support\Str;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua product yang sudah ada
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('Tidak ada produk ditemukan. Jalankan seeder ProductSeeder terlebih dahulu.');
            return;
        }

        foreach ($products as $product) {
            StockTransaction::create([
                'product_id' => $product->id,
                'type' => 'in', // atau 'out'
                'quantity' => rand(10, 100),
                'date' => now()->format('Y-m-d'),
                'status' => 'pending', // atau 'confirmed'
                'notes' => 'Barang masuk otomatis untuk testing',
            ]);
        }

        $this->command->info('Stock transactions berhasil dibuat.');
    }
}
