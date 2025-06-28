<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'supplier_id',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'min_stock',
        'stock',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Accessor for backward compatibility if you want to use 'code' in views
    public function getCodeAttribute()
    {
        return $this->sku;
    }

    // Mutator for backward compatibility
    public function setCodeAttribute($value)
    {
        $this->attributes['sku'] = $value;
    }
}
