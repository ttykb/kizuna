<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

define('employees', [
    "森泉　登美夫",
    "芹澤　力飛",
    "川口　義幸",
    "山田　武徳",
    "奥村　蓮",
    "阿部　龍馬",
    "佐々木　朔也",
    "橋本　力弥",
    "木田　涼太",
    "奥村　亜斗夢",
    "稲見　誠一",
    "中村　功成",
    "大澤　颯梓",
    "谷島　健太郎",
    "小林　昌之",
    "三浦　快",
    "中島　陸",
    "大村　啓太",
    "大畠　瀬那",
    "小山　将虎",
    "山科　康己",
    "本間　勇翔",
    "宮本　勝郎",
    "青山　海生",
    "加藤　瑠惟",
    "飛山　龍輝",
    "清浦　健太",
    "高橋　光幸",
    "今荘　渉",
    "大畠　悠那",
    "門前　大志"
]);

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        foreach (employees as $Employee) {
            DB::table('employees')->insert([
                'name' => $Employee,
                'display_order' => $i,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $i++;
        }
    }
}
