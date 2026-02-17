<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Housing extends Model
{
    use HasFactory;

    protected $primaryKey = 'housing_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'housing_name',
        'location',
        'capacity',
        'status'
    ];

    protected $casts = [
        'capacity' => 'integer'
    ];

    // relationships
    public function flocks()
    {
        return $this->hasMany(Flock::class, 'housing_id', 'housing_id');
    }

    // scope
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    // get current occupancy count
    public function getCurrentOccupancy()
    {
        return $this->flocks()->where('status', 'active')->sum('bird_count');
    }
    // get available capacity
    public function getAvailableCapacity()
    {
        return $this->capacity - $this->getCurrentOccupancy();
    }
    // check if housing has availbale space
    public function hasAvailableSpace($requiredSpace = 1)
    {
        return $this->getAvailableCapacity() >= $requiredSpace;
    }
    // get occupancy percentage
    public function getOccupancyPercentage()
    {
        if ($this->capacity == 0) {
            return 0;
        }
        return round(($this->getCurrentOccupancy() / $this->capacity) * 100, 2);
    }
}
