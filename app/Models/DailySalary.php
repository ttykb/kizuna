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

    public function scopeEmployeeIdMax($query)
    {
        return $query->max('employee_id');
    }
}
