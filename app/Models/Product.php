<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'supplier_id', 'name', 'sku',
        'description', 'purchase_price', 'selling_price',
        'image', 'minimum_stock', 'unit'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['current_stock'];

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

    protected function currentStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Prioritas 1: Gunakan hasil agregat dari query yang efisien jika ada.
                // Ini digunakan di halaman daftar produk (index).
                if (isset($this->attributes['stock_in_sum']) && isset($this->attributes['stock_out_sum'])) {
                    return ($this->attributes['stock_in_sum'] ?? 0) - ($this->attributes['stock_out_sum'] ?? 0);
                }
                
                // Prioritas 2: Hitung manual jika query efisien tidak dijalankan.
                // Ini digunakan di halaman detail, form, dll.
                // Kita memuat relasi 'stockTransactions' untuk menghindari N+1 problem.
                if ($this->relationLoaded('stockTransactions')) {
                    return $this->stockTransactions->where('type', 'Masuk')->sum('quantity') - $this->stockTransactions->where('type', 'Keluar')->sum('quantity');
                }

                // Fallback (paling tidak efisien, tapi aman): Hitung langsung jika relasi belum dimuat.
                return $this->stockTransactions()->where('type', 'Masuk')->sum('quantity') - $this->stockTransactions()->where('type', 'Keluar')->sum('quantity');
            }
        );
    }
}
