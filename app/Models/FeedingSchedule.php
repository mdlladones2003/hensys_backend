<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'feeding_schedule_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'flock_id',
        'feed_type',
        'feeding_time',
        'quantity_kg'
    ];

    protected $casts = [
        'quantity_kg' => 'decimal:2',
    ];

    // relationships
    public function flock()
    {
        return $this->belongsTo(Flock::class, 'flock_id', 'flock_id');
    }

    // scope
    public function scopeByFeedType($query, $feedType)
    {
        return $query->where('feed_type', $feedType);
    }
    public function scopeOrderByTime($query)
    {
        return $query->orderBy('feeding_time');
    }
    public function scopeMorning($query)
    {
        return $query->whereTime('feeding_time', '<', '12:00:00');
    }
    public function scopeAfternoon($query)
    {
        return $query->whereTime('feeding_time', '>=', '12:00:00')
                     ->whereTime('feeding_time', '<', '18:00:00');
    }
    public function scopeEvening($query)
    {
        return $query->whereTime('feeding_time', '>=', '18:00:00');
    }

    // formatted feeding time
    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->feeding_time)->format('g:i A');
    }
    // total daily feed for a flock
    public static function getTotalDailyFeed($flockId)
    {
        return static::where('flock_id', $flockId)->sum('quantity_kg');
    }
}
