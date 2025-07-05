<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
            $table->enum('type', ['masuk', 'keluar']);
            $table->unsignedInteger('quantity');
            $table->date('date');
            $table->text('notes')->nullable();

            // Kolom untuk alur kerja approval
            $table->string('status')->default('pending')->comment('Status: pending, completed, rejected');
            $table->foreignId('processed_by_user_id')
                  ->nullable()
                  ->comment('User (Staff) yang memproses')
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
