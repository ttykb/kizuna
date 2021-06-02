<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Workplace;
use App\Models\Employee;
use App\Models\Attendance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 現場名一覧
        $workplaceList =  Workplace::WorkplaceList();
        // 従業員情報
        $employees = Employee::OrderIdAsc()->get();

        // 現在日付
        $nowDate = new Carbon();

        // 表示年月
        if (is_null($request->viewY) && is_null($request->viewM) && is_null($request->viewD)) {
            $date = $nowDate;
        } else {
            $date = new Carbon($request->viewY . '-' . $request->viewM . '-' . $request->viewD);
        }
        $viewY = date('Y', strtotime($date));
        $viewM = sprintf('%02d', date('m', strtotime($date)));
        $viewDate = date('Y-m-01', strtotime($date));

        // 月初日
        $biginningOfTheMonth = new Carbon($date);
        $biginningOfTheMonth->startOfMonth();
        $biginningOfTheMonth = date('Y-m-d', strtotime($biginningOfTheMonth));
        // 月末日
        $endOfTheMonth = new Carbon($date);
        $endOfTheMonth->EndOfMonth();
        $endOfTheMonth = date('Y-m-d', strtotime($endOfTheMonth));
        // 翌月
        $nextMonth = new Carbon($date);
        $nextMonth = date('Y-m-d', strtotime($biginningOfTheMonth . '+1 month'));

        $salaryArray = [];
        // （従業員別）日当金額
        $latestDailySalaries = DB::table('daily_salaries')
            ->select('employee_id', DB::raw('MAX(app_start_date) as max_app_start_date'))
            ->where('app_start_date', '<', $nextMonth)
            ->groupBy('employee_id');
        $dailySalaries = DB::table('daily_salaries')
            ->select('daily_salaries.employee_id', 'app_start_date', 'price')
            ->joinSub($latestDailySalaries, 'latest_daily_salaries', function ($join) {
                $join->on('daily_salaries.employee_id', '=', 'latest_daily_salaries.employee_id')
                    ->on('daily_salaries.app_start_date', '=', 'latest_daily_salaries.max_app_start_date');
            })->where('app_start_date', '<', $nextMonth)
            ->groupBy('daily_salaries.employee_id');

        // （従業員別）残業金額
        $latestOvertimeFees = DB::table('overtime_fees')
            ->select('employee_id', DB::raw('MAX(app_start_date) as max_app_start_date'))
            ->where('app_start_date', '<', $nextMonth)
            ->groupBy('employee_id');
        $overtimeFees = DB::table('overtime_fees')
            ->select('overtime_fees.employee_id', 'app_start_date', 'price')
            ->joinSub($latestOvertimeFees, 'latest_overtime_fees', function ($join) {
                $join->on('overtime_fees.employee_id', '=', 'latest_overtime_fees.employee_id')
                    ->on('overtime_fees.app_start_date', '=', 'latest_overtime_fees.max_app_start_date');
            })->where('app_start_date', '<', $nextMonth)
            ->groupBy('overtime_fees.employee_id');

        // （従業員別）出勤回数×日当金額（講習除く）
        $workCount = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, count(worktype_id) * price as sum_daily_salaly'))
            ->joinSub($dailySalaries, 'daily_salaries', function ($join) {
                $join->on('attendances.employee_id', '=', 'daily_salaries.employee_id');
            })->whereNotIn('worktype_id', [3, 4])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->groupBy('attendances.employee_id')->get();

        // （従業員別）出勤回数×日当金額（講習のみ）
        $shortCourseCount = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, count(worktype_id) * (price / 2) as sum_daily_salaly'))
            ->joinSub($dailySalaries, 'daily_salaries', function ($join) {
                $join->on('attendances.employee_id', '=', 'daily_salaries.employee_id');
            })->where('worktype_id', [3])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->groupBy('attendances.employee_id')->get();

        // （従業員別）残業時間（単位：1時間）×残業金額
        $overtimeSum = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, (sum(time_to_sec(overtime)) / 60 / 60) * overtime_fees.price as sum_overtime'))
            ->joinSub($overtimeFees, 'overtime_fees', function ($join) {
                $join->on('attendances.employee_id', '=', 'overtime_fees.employee_id');
            })->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->groupBy('attendances.employee_id')->get();

        //（従業員別）手当代
        $allowanceSum = Attendance::select(Attendance::raw('employee_id, sum(allowance) as sum_allowance'))
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->groupBy('employee_id')
            ->get();

        //（従業員別）送迎代
        $pickupPriceSum = Attendance::select(Attendance::raw('employee_id, sum(price) as sum_price'))
            ->join('pickups', 'pickup_id', '=', 'pickups.id')
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->groupBy('employee_id')
            ->get();

        // 日払い済み分集計
        // （従業員別）出勤回数×日当金額（講習除く）
        $paidDailySalaries = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, is_daily_payment, worktype_id, null pickup_id, null as overtime, price'))
            ->joinSub($dailySalaries, 'daily_salaries', function ($join) {
                $join->on('attendances.employee_id', '=', 'daily_salaries.employee_id');
            })
            ->Where('is_daily_payment', '=', '1')
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])
            ->whereNotIn('worktype_id', [3, 4]);

        // （従業員別）出勤回数×日当金額（講習のみ）
        $paidShortCourse = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, is_daily_payment, worktype_id, null pickup_id, null as overtime, price / 2 as price'))
            ->joinSub($dailySalaries, 'paid_short_coursies', function ($join) {
                $join->on('attendances.employee_id', '=', 'paid_short_coursies.employee_id');
            })
            ->Where([
                ['is_daily_payment', '=', '1'],
                ['worktype_id', '=', '3']
            ])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth]);

        // （従業員別）送迎代
        $paidPickups = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, is_daily_payment, worktype_id, pickup_id, null overtime, price'))
            ->join('pickups', function ($join) {
                $join->on('attendances.pickup_id', '=', 'pickups.id');
            })
            ->Where([
                ['is_daily_payment', '=', '1'],
                ['worktype_id', '<>', '4']
            ])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth]);

        // （従業員別）残業時間×残業代
        $paidOvertimeFees = DB::table('attendances')
            ->select(DB::raw('attendances.employee_id, attendances.base_date, is_daily_payment, worktype_id, null pickup_id, overtime, time_to_sec(overtime) / 60 / 60 * overtime_fees.price as price'))
            ->joinSub($overtimeFees, 'overtime_fees', function ($join) {
                $join->on('attendances.employee_id', '=', 'overtime_fees.employee_id');
            })
            ->Where([
                ['is_daily_payment', '=', '1'],
                ['worktype_id', '<>', '4']
            ])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth]);

        // （従業員別）手当代
        $paidAllowance = DB::table('attendances')
            ->select(DB::raw('employee_id, base_date, is_daily_payment, worktype_id, null pickup_id, null overtime, allowance as price'))
            ->Where([
                ['is_daily_payment', '=', '1'],
                ['worktype_id', '<>', '4']
            ])
            ->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth]);

        $paidDailyAll = $paidDailySalaries
            ->unionAll($paidShortCourse)
            ->unionAll($paidPickups)
            ->unionAll($paidOvertimeFees)
            ->unionAll($paidAllowance);

        $dailyPaymentSum = DB::table('attendances')
            ->select(DB::raw('employee_id, sum(price) as sum_daily_payment'))
            ->from($paidDailyAll, 'paid_daily_all')
            ->groupBy('employee_id')->get();

        foreach ($workCount as $result) {
            $salaryArray[$result->employee_id]['daily_salaly'] = $result->sum_daily_salaly;
        }
        foreach ($shortCourseCount as $result) {
            $salaryArray[$result->employee_id]['daily_salaly'] = $result->sum_daily_salaly;
        }
        foreach ($overtimeSum as $result) {
            $salaryArray[$result->employee_id]['overtime'] = $result->sum_overtime;
        }
        foreach ($allowanceSum as $result) {
            $salaryArray[$result->employee_id]['allowance'] = $result->sum_allowance;
        }
        foreach ($pickupPriceSum as $result) {
            $salaryArray[$result->employee_id]['pickup'] = $result->sum_price;
        }
        foreach ($salaryArray as $key => $value) {
            $salaryArray[$key]['total'] = array_sum($salaryArray[$key]);
        }
        foreach ($dailyPaymentSum as $result) {
            $salaryArray[$result->employee_id]['dailyPayment'] = $result->sum_daily_payment;
        }

        return view('salary', compact('workplaceList', 'employees', 'nowDate', 'viewY', 'viewM', 'viewDate', 'salaryArray'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
