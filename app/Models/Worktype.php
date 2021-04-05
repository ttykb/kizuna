<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worktype extends Model
{
    use SoftDeletes;

    public static function selectList() {
        $worktypes = Worktype::all();
        $arrayList = array();
        $arrayList += array("" => "");
        foreach ($worktypes as $worktype) {
            $arrayList += array($worktype->id => $worktype->name);
        }

        return $arrayList;
    }


    public function attendances() {
        return $this->hasMany(Attendances::class);
    }

    public function scopeSelectName($query) {
        return $query->select('name');
    }

    public function scopeIdAt($query, $id) {
        return $query->where('id', $id);
    }

    public function ScopeOrderIdAsc($query) {
        return $query->orderBy('id', 'asc');
    }
}
