<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvertimeFee extends Model
{
    use SoftDeletes, HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeIdAt($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeEmployeeIdAt($query, $id)
    {
        return $query->where('employee_id', $id);
    }

    public function scopeAppStartDateAt($query, $date)
    {
        return $query->where('app_start_date', $date);
    }

    public function scopeEmployeeIdMax($query)
    {
        return $query->max('employee_id');
    }
}
