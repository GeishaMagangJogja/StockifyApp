<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'current_stock',
        'min_stock',
        'unit',
    ];

    protected $attributes = [
        'current_stock' => 0,
        'min_stock' => 0,
        'unit' => 'pcs',
    ];

    protected $appends = ['stock_status'];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Accessors
    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match (true) {
                $this->current_stock <= 0 => 'out_of_stock',
                $this->current_stock <= $this->min_stock => 'low_stock',
                default => 'in_stock',
            }
        );
    }

    // Helper method for calculating available stock
    public function availableStock()
    {
        if (isset($this->attributes['stock_in_sum'])) {
            return ($this->attributes['stock_in_sum'] ?? 0) - ($this->attributes['stock_out_sum'] ?? 0);
        }

        if ($this->relationLoaded('stockTransactions')) {
            return $this->stockTransactions->where('type', 'Masuk')->sum('quantity') -
                   $this->stockTransactions->where('type', 'Keluar')->sum('quantity');
        }

        return $this->stockTransactions()->where('type', 'Masuk')->sum('quantity') -
               $this->stockTransactions()->where('type', 'Keluar')->sum('quantity');
    }
    // Di Model Product
public function getCurrentStockAttribute()
{
    return $this->stockTransactions()
        ->selectRaw('SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END) as stock')
        ->value('stock') ?? 0;
}
}
