<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke produk, user, dan supplier
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->comment('User yang mengajukan permintaan')->constrained('users')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            
            // Detail transaksi
            $table->enum('type', ['Masuk', 'Keluar']);
            $table->unsignedInteger('quantity');
            $table->timestamp('date');
            $table->text('notes')->nullable();

            // Kolom untuk alur kerja approval
            $table->string('status')->default('Pending')->comment('Status: Pending, Diterima, Dikeluarkan, Ditolak');
            $table->foreignId('processed_by')->nullable()->comment('User (Staff) yang memproses')->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};