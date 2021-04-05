<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public static function selectTypeList() {
        $arrayList = array();

        $arrayList += array("" => "");
        $arrayList += array('1' => '従業員');
        $arrayList += array('2' => '現場');
        $arrayList += array('3' => 'シフト');

        return $arrayList;
    }
}
