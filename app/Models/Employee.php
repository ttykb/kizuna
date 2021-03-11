<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use SoftDeletes;

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeNameAt($query, $name) {
        return $query->where('name', $name);
    }

    public function scopeDisplayOrderDesc($query) {
        return $query->orderBy('display_order', 'desc');
    }
}
