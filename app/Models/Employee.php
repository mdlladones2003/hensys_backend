<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'employee_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'email',
        'password',
        'phone',
        'hire_date',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'password'  => 'hashed'
    ];

    // full name of the employee
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // relationships
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'employee_id', 'employee_id');
    }
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'employee_id', 'employee_id');
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
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }
    public function scopeManagers($query)
    {
        return $query->where('position', 'manager');
    }
    public function scopeVeterinarians($query)
    {
        return $query->where('position', 'veterinarian');
    }

    // get today's work schedule
    public function getTodaySchedule()
    {
        return $this->workSchedules()->whereDate('work_date', today())->first();
    }
    // check if employee is working today
    public function isWorkingToday()
    {
        return $this->getTodaySchedule() !== null;
    }
    // get years of service
    public function getYearsOfService()
    {
        return $this->hire_date->diffInYears(now());
    }

    // check if employee is a manager
    public function isManager()
    {
        return $this->position === 'manager';
    }
    // check if employee is a veterinarian
    public function isVeterinarian()
    {
        return $this->position === 'veterinarian';
    }
}
