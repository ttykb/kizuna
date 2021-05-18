<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvertimeFee extends Model
{
    use SoftDeletes, HasFactory;

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
