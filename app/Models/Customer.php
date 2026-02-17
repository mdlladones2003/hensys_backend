<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasUuids, Notifiable;

    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'address',
        'customer_type'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed'
    ];

    public function uniqueIds(): array
    {
        return ['customer_id'];
    }

    // full name of the customer
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // relationships
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id', 'customer_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    // scopes
    public function scopeRetail($query)
    {
        return $query->where('customer_type', 'retail');
    }
    public function scopeWholesale($query)
    {
        return $query->where('customer_type', 'wholesale');
    }

    // check if customer is wholesale
    public function isWholesale()
    {
        return $this->customer_type === 'wholesale';
    }
}
