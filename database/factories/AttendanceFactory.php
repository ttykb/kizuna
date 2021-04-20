<?php

namespace Database\Factories;

use Carbon\Carbon;

use App\Models\Attendance;
use App\Models\Worktype;
use App\Models\Workplace;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $worktypeList = array();
        $tempWorktypes = Worktype::OrderIdAsc()->get();
        foreach ($tempWorktypes as $key => $value) {
            array_push($worktypeList, $value['name']);
        }

        $workplaceList = array();
        $tempWorkplaces = Workplace::OrderIdAsc()->get();
        foreach ($tempWorkplaces as $key => $value) {
            array_push($workplaceList, $value['name']);
        }

        // 月初日
        $biginningOfTheMonth = new Carbon();
        $biginningOfTheMonth->startOfMonth();
        $biginningOfTheMonth = date('Y-m-d', strtotime($biginningOfTheMonth));
        // 月末日
        $endOfTheMonth = new Carbon();
        $endOfTheMonth->EndOfMonth();
        $endOfTheMonth = date('Y-m-d', strtotime($endOfTheMonth));

        return [
            'base_date' => $this->faker->dateTimeBetween($startDate = $biginningOfTheMonth, $endDate = $endOfTheMonth, $timezone = date_default_timezone_get()),
            'employee_id' => $this->faker->numberBetween(1,31),
            'worktype_id' => $this->faker->numberBetween(1,4),
            'workplace_id' => $this->faker->numberBetween(1,10),
            'pickup' => $this->faker->numberBetween(1,2),
            'overtime' => $this->faker->time,
            'is_daily_report' => $this->faker->boolean,
            'is_daily_payment' => $this->faker->boolean,
        ];
    }
}
