<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom 'unit' setelah kolom 'min_stock'
            $table->string('unit', 20)->default('pcs')->after('min_stock');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom 'unit' jika migrasi di-rollback
            $table->dropColumn('unit');
        });
    }
};