<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use SoftDeletes;

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeBaseDateAt($query, $base_date) {
        return $query->where('base_date', $base_date);
    }

    public function scopeEmployeeIdAt($query, $employee_id) {
        return $query->where('employee_id', $employee_id);
    }

    public function scopeWorkplaceIdAt($query, $workplace_id) {
        return $query->where('workplace_id', $workplace_id);
    }

    public function ScopeIsPickup($query) {
        return $query->where('is_pickup', true);
    }

    public function ScopeIsDailyReport($query) {
        return $query->where('is_daily_report', true);
    }
}
