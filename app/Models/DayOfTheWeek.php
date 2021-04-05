<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOfTheWeek extends Model
{
    public static function viewDayOfTheWeek($date) {

        $tempNum = date('w', strtotime($date));
        $result = "";
        switch($tempNum) {
            case 0:
                $result = "日";
                break;
            case 1:
                $result = "月";
                break;
            case 2:
                $result = "火";
                break;
            case 3:
                $result = "水";
                break;
            case 4:
                $result = "木";
                break;
            case 5:
                $result = "金";
                break;
            case 6:
                $result = "土";
                break;
        }

        return $result;
    }
}
