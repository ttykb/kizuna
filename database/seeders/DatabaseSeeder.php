<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            WorkplaceSeeder::class,
            WorktypeSeeder::class,
            EmployeeSeeder::class,
            PickupSeeder::class,
            DailySalarySeeder::class,
            OvertimeFeeSeeder::class,
        ]);
    }
}
