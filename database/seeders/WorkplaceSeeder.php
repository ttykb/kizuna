<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Workplaces = [
            "絆",
            "中川",
            "セイショウ",
            "シーテック",
            "佐々木工業",
            "風組",
            "第一健康江島",
            "匠",
            "川重産業",
            "東栄建設",
            "小島建設",
            "スタック"
        ];

        foreach($Workplaces as $Workplace) {
            DB::table('workplaces')->insert([
                'name' => $Workplace,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
