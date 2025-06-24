<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'name' => 'PT. Supplier Jaya',
            'address' => 'Jl. Industri No.1',
            'phone' => '081234567890',
            'email' => 'supplier@jaya.com'
        ]);
    }
}

