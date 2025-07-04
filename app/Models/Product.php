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

    protected $appends = ['stock_status', 'formatted_purchase_price', 'formatted_selling_price'];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'Tidak Berkategori'
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withDefault([
            'name' => 'Tidak Ada Supplier'
        ]);
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
            get: function () {
                $currentStock = $this->current_stock;

                if ($currentStock <= 0) {
                    return 'out_of_stock';
                }

                if ($currentStock <= $this->min_stock) {
                    return 'low_stock';
                }

                return 'in_stock';
            }
        );
    }

    protected function currentStock(): Attribute
    {
        return Attribute::make(
        get: function ($value) {
            // Selalu kembalikan value langsung dari database saat import
            return $value;
        },
        set: fn ($value) => $value
    );
    }

    protected function formattedPurchasePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp' . number_format($this->purchase_price, 0, ',', '.')
        );
    }

    protected function formattedSellingPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp' . number_format($this->selling_price, 0, ',', '.')
        );
    }

    // Helper methods
    public function updateStockFromTransactions()
    {
        $this->current_stock = $this->stockTransactions()
            ->selectRaw('SUM(CASE WHEN type = "Masuk" THEN quantity ELSE -quantity END) as stock')
            ->value('stock') ?? 0;

        $this->save();
    }

    
}
