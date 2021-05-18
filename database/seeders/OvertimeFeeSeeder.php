<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

define('overtimeFees', [
    "1" => 0,
    "2" => 1750,
    "3" => 1500,
    "4" => 1500,
    "5" => 1250,
    "6" => 1250,
    "7" => 1250,
    "8" => 1500,
    "9" => 1250,
    "10" => 1120,
    "11" => 1250,
    "12" => 1250,
    "13" => 1120,
    "14" => 1250,
    "15" => 1250,
    "16" => 1120,
    "17" => 1250,
    "18" => 1250,
    "19" => 1250,
    "20" => 1250,
    "21" => 1120,
    "22" => 1120,
    "23" => 1250,
    "24" => 1120,
    "25" => 1120,
    "26" => 1250,
    "27" => 1250,
    "28" => 1250,
    "29" => 1250,
    "30" => 1120,
    "31" => 1250
]);

class OvertimeFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (overtimeFees as $key => $value) {
            DB::table('overtime_fees')->insert([
                'employee_id' => $key,
                'price' => $value,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
