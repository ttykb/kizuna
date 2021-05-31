<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public static function editingTypeList()
    {
        $arrayList = array();

        $arrayList += array("" => "");
        $arrayList += array('1' => '追加');
        $arrayList += array('2' => '変更');
        $arrayList += array('3' => '削除');

        return $arrayList;
    }

    public static function lineTypeList()
    {
        $arrayList = array();

        $arrayList += array('1' => '従業員');
        $arrayList += array('2' => '現場');
        $arrayList += array('3' => 'シフト');
        $arrayList += array('4' => '送迎金額');

        return $arrayList;
    }

    public static function employeeItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '名前');
        $arrayList += array('2' => '日当金額');
        $arrayList += array('3' => '残業手当（1時間あたり）');

        return $arrayList;
    }

    public static function dailySalaryItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '金額');
        $arrayList += array('2' => '適用開始月');

        return $arrayList;
    }

    public static function overtimeFeeItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '金額');
        $arrayList += array('2' => '適用開始月');

        return $arrayList;
    }

    public static function workplaceItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '名前');

        return $arrayList;
    }

    public static function worktypeItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '名前');

        return $arrayList;
    }

    public static function pickupItemList()
    {
        $arrayList = array();

        $arrayList += array('1' => '金額');

        return $arrayList;
    }
}
