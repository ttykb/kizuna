<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseDate extends Model
{
    public static function selectYearList($date) {
        $arrayList = array();

        for ($i = date('Y-m-01', strtotime("-10 year", strtotime($date))); strtotime($i) <= strtotime($date); $i = date('Y-m-01', strtotime("+1 year", strtotime($i)))) {
            $tmpYear = date('Y', strtotime($i));
            $arrayList += array($tmpYear => $tmpYear);
        }

        return $arrayList;
    }

    public static function selectMonthList() {
        $arrayList = array();
        for ($i = 1; $i <= 12; $i++) {
            $arrayList += array(sprintf('%02d',$i) => $i);
        }

        return $arrayList;
    }
}
