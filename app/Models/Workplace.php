<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Workplace extends Model
{
    use SoftDeletes;

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeNameAt($query, $name) {
        return $query->where('name', $name);
    }
}
