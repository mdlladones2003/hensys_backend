<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flock extends Model
{
    use HasFactory;

    protected $primaryKey = 'flock_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'housing_id',
        'flock_name',
        'breed',
        'bird_count',
        'age_weeks',
        'arrival_date',
        'status'
    ];

    protected $casts = [
        'bird_count'    => 'integer',
        'age_weeks'     => 'integer',
        'arrival_date'  => 'date'
    ];

    // relationships
    public function housing()
    {
        return $this->belongsTo(Housing::class, 'housing_id', 'housing_id');
    }
    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class, 'flock_id', 'flock_id');
    }
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'flock_id', 'flock_id');
    }
    public function feedingSchedules()
    {
        return $this->hasMany(FeedingSchedule::class, 'flock_id', 'flock_id');
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

    // get latest health record
    public function getLatestHealthRecord()
    {
        return $this->healthRecords()->latest('check_date')->first();
    }
    // get total eggs produced
    public function getTotalEggsProduced()
    {
        return $this->productionBatches()->sum('total_eggs');
    }
    // get average daily production
    public function getAverageDailyProduction()
    {
        $totalEggs = $this->getTotalEggsProduced();
        $daysActive = $this->arrival_date->diffInDays(now());

        if ($daysActive == 0) {
            return 0;
        }

        return round($totalEggs / $daysActive, 2);
    }
    // get production efficiency (eggs per bird per day)
    public function getProductionEfficiency()
    {
        if ($this->bird_count == 0) {
            return 0;
        }

        return round($this->getAverageDailyProduction() / $this->bird_count, 2);
    }
    // update age in weeks
    public function updateAge()
    {
        $this->age_weeks = $this->arrival_date->diffInWeeks(now());
        $this->save();

        return $this->age_weeks;
    }
}
