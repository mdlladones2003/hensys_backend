<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'health_record_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'flock_id',
        'employee_id',
        'check_date',
        'health_status',
        'vaccination',
        'treatment',
        'notes'
    ];

    protected $casts = [
        'check_date' => 'date'
    ];

    // relationships
    public function flock()
    {
        return $this->belongsTo(Flock::class, 'flock_id', 'flock_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // scope
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('check_date', [$startDate, $endDate]);
    }
    public function scopeToday($query)
    {
        return $query->whereDate('check_date', today());
    }
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('check_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
    public function scopeByStatus($query, $status)
    {
        return $query->where('health_status', $status);
    }
    public function scopeWithVaccination($query)
    {
        return $query->whereNotNull('vaccination');
    }
    public function scopeWithTreatment($query)
    {
        return $query->whereNotNull('treatment');
    }

    // check if vaccination was administered
    public function hasVaccination()
    {
        return !is_null($this->vaccination);
    }
    // check if treatment was given
    public function hasTreatment()
    {
        return !is_null($this->treatment);
    }
}
