<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pickup extends Model
{
    use SoftDeletes;

    public static function selectList()
    {
        $Pickups = Pickup::all();
        $arrayList = array();
        $arrayList += array("" => "");

        foreach ($Pickups as $Pickup) {
            $arrayList += array($Pickup->id => $Pickup->price);
        }
        return $arrayList;
    }

    public function scopeSelectPrice($query)
    {
        return $query->select('price');
    }

    public function scopeIdAt($query, $id)
    {
        return $query->where('id', $id);
    }

    public function ScopeOrderIdAsc($query)
    {
        return $query->orderBy('id', 'asc');
    }
}
