<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    public function attendances()
    {
        return $this->hasMany(Attendances::class);
    }

    public function scopeIdAt($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeNameAt($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeDisplayOrderMax($query)
    {
        return $query->max('display_order');
    }

    public function scopeOrderIdAsc($query)
    {
        return $query->orderBy('id', 'asc');
    }

    public function scopeOrderIdDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeOrderDisplayOrderAsc($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    public function scopeOrderDisplayOrderDesc($query)
    {
        return $query->orderBy('display_order', 'desc');
    }
}
