<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

define('worktypes', [
    "日勤",
    "夜勤",
    "講習",
    "全休",
]);

class WorktypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(worktypes as $Worktype) {
            DB::table('worktypes')->insert([
                'name' => $Worktype,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
