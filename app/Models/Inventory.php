<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'quantity_available',
        'reorder_level',
        'last_updated'
    ];

    protected $casts = [
        'quantity_available'    => 'integer',
        'reorder_level'         => 'integer',
        'last_updated'          => 'datetime'
    ];

    // relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id', 'inventory_id');
    }

    // scope
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity_available', '<=', 'reorder_level');
    }
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_available', 0);
    }

    // check if inventory needs reorder
    public function needsReorder()
    {
        return $this->quantity_available <= $this->reorder_level;
    }
    // check if product is in stock
    public function inStock()
    {
        return $this->quantity_available > 0;
    }
}
