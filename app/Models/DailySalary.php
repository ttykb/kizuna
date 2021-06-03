<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalary extends Model
{
    use HasFactory;

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
