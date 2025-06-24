<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        Category::create(['name' => 'Elektronik', 'description' => 'Barang-barang elektronik']);
        Category::create(['name' => 'Pakaian', 'description' => 'Baju dan celana']);
        Category::create(['name' => 'Makanan', 'description' => 'Makanan & minuman']);
    }
}
