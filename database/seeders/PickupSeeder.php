<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Prices = [
            "500",
            "1000",
        ];

        foreach($Prices as $Price) {
            DB::table('pickups')->insert([
                'price' => $Price,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
