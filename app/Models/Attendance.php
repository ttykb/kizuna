<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use SoftDeletes, HasFactory;

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function worktype() {
        return $this->belongsTo(Worktype::class);
    }

    public function workplace() {
        return $this->belongsTo(Workplace::class);
    }

    public function scopeSelectEmployeeId($query) {
        return $query->select('employee_id');
    }

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeBaseDateAt($query, $base_date) {
        return $query->where('base_date', $base_date);
    }

    public function scopeBaseDateMonthAt($query, $base_date) {

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

    public function ScopeGroupEmployeeId($query) {
        return $query->groupBy('employee_id');
    }

    public function ScopeOrderBaseDateAsc($query) {
        return $query->orderBy('base_date', 'asc');
    }

    public function ScopeOrderBaseDateDesc($query) {
        return $query->orderBy('base_date', 'desc');
    }

    public function ScopeOrderEmployeeIdAsc($query) {
        return $query->orderBy('employee_id', 'asc');
    }

    public function ScopeOrderEmployeeIdDesc($query) {
        return $query->orderBy('employee_id', 'desc');
    }
}
