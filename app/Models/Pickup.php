<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    public static function selectList() {
        $arrayList = array();
        $arrayList += array("" => "");
        $arrayList += array("1" => "○");
        $arrayList += array("0" => "×");

        return $arrayList;
    }
}
