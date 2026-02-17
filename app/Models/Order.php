<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'order_date',
        'delivery_date',
        'delivery_address',
        'status',
        'total_amount'
    ];

    protected $casts = [
        'order_date'    => 'date',
        'delivery_date' => 'date',
        'total_amount'  => 'decimal:2'
    ];

    // relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    // scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    public function scopeDueToday($query)
    {
        return $query->whereDate('delivery_date', today());
    }
    public function scopeScheduledBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('delivery_date', [$startDate, $endDate]);
    }

    // calculate and update total amount from items
    public function calculateTotal()
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();

        return $this->total_amount;
    }
    // check if order is paid
    public function isPaid()
    {
        return $this->payment && $this->payment->status === 'completed';
    }
}
