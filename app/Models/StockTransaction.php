<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasFactory;
    
    // PERUBAHAN: Konstanta diseragamkan ke huruf kapital agar konsisten dengan controller
    const TYPE_MASUK = 'Masuk';
    const TYPE_KELUAR = 'Keluar';

    // Konstanta status tetap
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'type',
        'quantity',
        'date',
        'status',
        'notes',
        'processed_by_user_id',
        // PERUBAHAN: Menambahkan field stock sebelum dan sesudah untuk audit yang lebih baik
        'previous_stock',
        'current_stock',
    ];

    protected $casts = [
        // PERUBAHAN: Cast 'date' ke datetime untuk presisi waktu
        'date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function processedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    // PERUBAHAN: Method ini dibuat lebih robust, tidak terpengaruh huruf besar/kecil.
    // Ini menjadi satu-satunya cara untuk memeriksa apakah transaksi adalah "Masuk".
    public function isTypeMasuk(): bool
    {
        return strtolower($this->type) === 'masuk';
    }

    public function isPending(): bool
    {
        return strtolower($this->status) === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return strtolower($this->status) === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return strtolower($this->status) === self::STATUS_REJECTED;
    }
}