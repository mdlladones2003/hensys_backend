<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'inventory_id',
        'production_batch_id',
        'transaction_type',
        'quantity',
        'transaction_date'
    ];

    protected $casts = [
        'quantity'          => 'integer',
        'transaction_date'  => 'date'
    ];

    // relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
    }
    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id', 'production_batch_id');
    }

    // scope
    public function scopeProduction($query)
    {
        return $query->where('transaction_type', 'Production');
    }
    public function scopeSales($query)
    {
        return $query->where('transaction_type', 'Sale');
    }
    public function scopeWaste($query)
    {
        return $query->where('transaction_type', 'Waste');
    }
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // check if transaction is an increase
    public function isIncrease()
    {
        return $this->quantity > 0;
    }
    // check if transaction is a decrease
    public function isDecrease()
    {
        return $this->quantity < 0;
    }
}
