<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create(['name' => 'PT. Elektronik Maju', 'address' => 'Jl. ABC 123', 'phone' => '081...']);
        Supplier::create(['name' => 'CV. Garmen Sejahtera', 'address' => 'Jl. DEF 456', 'phone' => '082...']);
    }
}