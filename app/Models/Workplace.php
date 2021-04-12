<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workplace extends Model
{
    use SoftDeletes;

    public static function selectList() {
        $workplaces = Workplace::all();
        $arrayList = array();
        $arrayList += array("" => "");
        foreach ($workplaces as $workplace) {
            $arrayList += array($workplace->id => $workplace->name);
        }

        return $arrayList;
    }

    public static function WorkplaceList() {
        $workplaces = Workplace::all();
        $arrayList = array();
        foreach ($workplaces as $workplace) {
            $arrayList += array($workplace->id => $workplace->name);
        }

        return $arrayList;
    }

    public function attendances() {
        return $this->hasMany(Attendances::class);
    }

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeNameAt($query, $name) {
        return $query->where('name', $name);
    }

    public function ScopeOrderIdAsc($query) {
        return $query->orderBy('id', 'asc');
    }
}
