<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

define('dailySalaries', [
    "1" => 0,
    "2" => 14000,
    "3" => 13000,
    "4" => 12000,
    "5" => 11000,
    "6" => 11500,
    "7" => 10000,
    "8" => 12000,
    "9" => 10000,
    "10" => 9500,
    "11" => 10000,
    "12" => 10000,
    "13" => 9000,
    "14" => 10000,
    "15" => 9000,
    "16" => 10500,
    "17" => 10000,
    "18" => 10000,
    "19" => 10000,
    "20" => 10000,
    "21" => 9000,
    "22" => 9000,
    "23" => 10000,
    "24" => 9500,
    "25" => 9000,
    "26" => 10000,
    "27" => 10000,
    "28" => 10000,
    "29" => 11500,
    "30" => 9000,
    "31" => 10000
]);

class DailySalarySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (dailySalaries as $key => $value) {
            DB::table('daily_salaries')->insert([
                'employee_id' => $key,
                'price' => $value,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
