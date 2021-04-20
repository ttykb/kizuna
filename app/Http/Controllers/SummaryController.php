<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Attendance;
use App\Models\Workplace;
use App\Models\Employee;
use App\Models\Summary;

class SummaryController extends Controller
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

        $summaryArray = [];
        // 集計結果（合計部分）
        $totalWorkplace = Attendance::select(Attendance::raw('workplace_id, count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('workplace_id'))->orderBy(Attendance::raw('workplace_id'))->get();
        $totalWorkplaceCount = Attendance::select(Attendance::raw('count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->get();
        $totalIsDailyReport = Attendance::select(Attendance::raw('count(is_daily_report) as cnt_is_daily_report'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->get();
        $totalOvertimeSummary = Attendance::select(Attendance::raw('sec_to_time(sum(time_to_sec(overtime))) as sum_overtime'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->get();
        $totalIsPickupSummary = Attendance::select(Attendance::raw('count(pickup) as cnt_pickup'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->get();

        foreach ($totalWorkplace as $result) {
            $summaryArray['total'][$result->workplace_id] = $result->cnt_workplace_id;
        }
        foreach ($totalWorkplaceCount as $result) {
            $summaryArray['total']['total_count'] = $result->cnt_workplace_id;
        }
        foreach ($totalIsDailyReport as $result) {
            $summaryArray['total']['is_daily_report'] = $result->cnt_is_daily_report;
        }
        foreach ($totalOvertimeSummary as $result) {
            $summaryArray['total']['overtime'] = $result->sum_overtime;
        }
        foreach ($totalIsPickupSummary as $result) {
            $summaryArray['total']['pickup'] = $result->cnt_pickup;
        }

        if (Route::is('summary.employee')) {
            // 従業員情報
            $employees = Employee::OrderIdAsc()->get();

            // 集計結果
            $workplaceSummary = Attendance::select(Attendance::raw('employee_id, workplace_id, count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('employee_id, workplace_id'))->orderBy(Attendance::raw('employee_id, workplace_id'))->get();
            $workplaceCountSummary = Attendance::select(Attendance::raw('employee_id, count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('employee_id'))->orderBy(Attendance::raw('employee_id'))->get();
            $isDailyReportSummary = Attendance::select(Attendance::raw('employee_id, count(is_daily_report) as cnt_is_daily_report'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('employee_id'))->orderBy(Attendance::raw('employee_id'))->get();
            $overtimeSummary = Attendance::select(Attendance::raw('employee_id, sec_to_time(sum(time_to_sec(overtime))) as sum_overtime'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('employee_id'))->orderBy(Attendance::raw('employee_id'))->get();
            $isPickupSummary = Attendance::select(Attendance::raw('employee_id, count(pickup) as cnt_pickup'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('employee_id'))->orderBy(Attendance::raw('employee_id'))->get();

            foreach ($employees as $employee) {
                foreach ($workplaceSummary as $result) {
                    if ($result->employee_id == $employee->id) {
                        $summaryArray[$employee->id][$result->workplace_id] = $result->cnt_workplace_id;
                    }
                }
                foreach ($workplaceCountSummary as $result) {
                    if ($result->employee_id == $employee->id) {
                        $summaryArray[$employee->id]['total_count'] = $result->cnt_workplace_id;
                    }
                }
                foreach ($isDailyReportSummary as $result) {
                    if ($result->employee_id == $employee->id) {
                        $summaryArray[$employee->id]['is_daily_report'] = $result->cnt_is_daily_report;
                    }
                }
                foreach ($overtimeSummary as $result) {
                    if ($result->employee_id == $employee->id) {
                        $summaryArray[$employee->id]['overtime'] = $result->sum_overtime;
                    }
                }
                foreach ($isPickupSummary as $result) {
                    if ($result->employee_id == $employee->id) {
                        $summaryArray[$employee->id]['pickup'] = $result->cnt_pickup;
                    }
                }
            }

            return view('/summary/employee', compact('employees', 'workplaceList', 'nowDate', 'date', 'viewY', 'viewM', 'viewDate', 'summaryArray'));
        } elseif (Route::is('summary.daily')) {

            // 表示日付
            $dayList = [];
            for ($i = $biginningOfTheMonth; strtotime($i) <= strtotime($endOfTheMonth); $i = date('Y-m-d', strtotime("+1 day", strtotime($i)))) {
                $dayList[] = $i;
            }

            // 集計結果
            $workplaceSummary = Attendance::select(Attendance::raw('base_date, workplace_id, count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('base_date, workplace_id'))->orderBy(Attendance::raw('base_date, workplace_id'))->get();
            $workplaceCountSummary = Attendance::select(Attendance::raw('base_date, count(workplace_id) as cnt_workplace_id'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('base_date'))->orderBy(Attendance::raw('base_date'))->get();
            $isDailyReportSummary = Attendance::select(Attendance::raw('base_date, count(is_daily_report) as cnt_is_daily_report'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('base_date'))->orderBy(Attendance::raw('base_date'))->get();
            $overtimeSummary = Attendance::select(Attendance::raw('base_date, sec_to_time(sum(time_to_sec(overtime))) as sum_overtime'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('base_date'))->orderBy(Attendance::raw('base_date'))->get();
            $isPickupSummary = Attendance::select(Attendance::raw('base_date, count(pickup) as cnt_pickup'))->whereBetween('base_date', [$biginningOfTheMonth, $endOfTheMonth])->groupBy(Attendance::raw('base_date'))->orderBy(Attendance::raw('base_date'))->get();

            foreach ($dayList as $day) {
                foreach ($workplaceSummary as $result) {
                    if ($result->base_date == $day) {
                        $summaryArray[$day][$result->workplace_id] = $result->cnt_workplace_id;
                    }
                }
                foreach ($workplaceCountSummary as $result) {
                    if ($result->base_date == $day) {
                        $summaryArray[$day]['total_count'] = $result->cnt_workplace_id;
                    }
                }
                foreach ($isDailyReportSummary as $result) {
                    if ($result->base_date == $day) {
                        $summaryArray[$day]['is_daily_report'] = $result->cnt_is_daily_report;
                    }
                }
                foreach ($overtimeSummary as $result) {
                    if ($result->base_date == $day) {
                        $summaryArray[$day]['overtime'] = $result->sum_overtime;
                    }
                }
                foreach ($isPickupSummary as $result) {
                    if ($result->base_date == $day) {
                        $summaryArray[$day]['pickup'] = $result->cnt_pickup;
                    }
                }
            }

            return view('/summary/daily', compact('dayList', 'workplaceList', 'nowDate', 'date', 'viewY', 'viewM', 'viewDate', 'summaryArray'));
        }
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
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function show(Summary $summary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function edit(Summary $summary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Summary $summary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Summary $summary)
    {
        //
    }
}
