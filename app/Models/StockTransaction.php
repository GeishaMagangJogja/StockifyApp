<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    use HasFactory;
    const TYPE_MASUK = 'masuk';
    const TYPE_KELUAR = 'keluar';

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
        'processed_by_user_id', // Tambahan field untuk tracking siapa yang memproses
    ];

    protected $casts = [
        'date' => 'date',
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

    // Relasi untuk user yang memproses transaksi
    public function processedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }
    // Di StockTransaction model
  public function isTypeMasuk()
    {
        return strtolower($this->type) === self::TYPE_MASUK;
    }

    public function isPending()
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
