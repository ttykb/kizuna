<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Worktype extends Model
{
    use SoftDeletes;

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }
}
