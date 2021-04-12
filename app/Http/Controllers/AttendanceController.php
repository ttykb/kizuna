<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Worktype;
use App\Models\Workplace;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        // 月初日
        $biginningOfTheMonth = new Carbon($date);
        $biginningOfTheMonth->startOfMonth();
        $biginningOfTheMonth = date('Y-m-d', strtotime($biginningOfTheMonth));
        // 月末日
        $endOfTheMonth = new Carbon($date);
        $endOfTheMonth->EndOfMonth();
        $endOfTheMonth = date('Y-m-d', strtotime($endOfTheMonth));

        // 週初め
        $startDayOfTheWeek = date('w', strtotime($date));
        $earlyWeek = date('Y-m-d', strtotime("-$startDayOfTheWeek day", strtotime($date)));
        if (strtotime($earlyWeek) < strtotime($biginningOfTheMonth)) {
            $earlyWeek = $biginningOfTheMonth;
        } else {
            $earlyWeek = $earlyWeek;
        }
        // 週末
        $endDayOfTheWeek = 6;
        $endDayOfTheWeek = $endDayOfTheWeek - date('w', strtotime($earlyWeek));
        $weekend = date('Y-m-d', strtotime("+$endDayOfTheWeek day", strtotime($earlyWeek)));
        if (strtotime($weekend) > strtotime($endOfTheMonth)) {
            $weekend = $endOfTheMonth;
        } else {
            $weekend = $weekend;
        }

        // 表示日付
        $dayList = array();
        for ($i = $earlyWeek; strtotime($i) <= strtotime($weekend); $i = date('Y-m-d', strtotime("+1 day", strtotime($i)))) {
            $dayList += array(date('w', strtotime($i)) => $i);
        }

        // 勤怠種別一覧
        $worktypeList =  Worktype::selectList();
        // 現場名一覧
        $workplaceList =  Workplace::selectList();

        // 勤怠記録
        $tempAttendances = Attendance::whereBetween('base_date', [$earlyWeek, $weekend])->OrderBaseDateAsc()->OrderEmployeeIdAsc()->get();
        $attendances = [];
        foreach ($employees as $employee) {
            foreach ($tempAttendances as $tempAttendance) {
                if ($tempAttendance->employee_id == $employee->id) {
                    $tempArray = array(
                        'id' => $tempAttendance->id,
                        'worktype' => $tempAttendance->worktype_id,
                        'workplace' => $tempAttendance->workplace_id,
                        'is_pickup' => $tempAttendance->is_pickup,
                        'overtime' => $tempAttendance->overtime,
                        'is_daily_report' => $tempAttendance->is_daily_report,
                        'is_daily_payment' => $tempAttendance->is_daily_payment,
                        'note' => $tempAttendance->note
                    );
                    if ($tempArray) {
                        $attendances[$employee->id][$tempAttendance->base_date] = $tempArray;
                    }
                }
            }
        }

        return view('index', compact('employees', 'attendances', 'nowDate', 'date', 'viewY', 'viewM', 'earlyWeek', 'weekend', 'dayList', 'worktypeList', 'workplaceList'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // 更新処理
        $this->update($request);

        // リダイレクト処理
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach ($request->request as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (!empty(array_filter($subValue))) {
                        var_dump($subValue);
                        if (!empty($subValue['id'])) {
                            $attendance = Attendance::IdAt($subValue['id'])->first();
                            $attendance->id = $subValue['id'];
                            $attendance->worktype_id = $subValue['worktype'];
                            $attendance->workplace_id = $subValue['workplace'];
                            $attendance->is_pickup = $subValue['is_pickup'];
                            if (is_null($subValue['overtime'])) {
                                $attendance->overtime = null;
                            } else {
                                $attendance->overtime = date('H:i:00', strtotime($subValue['overtime']));
                            }
                            $attendance->is_daily_report = $subValue['is_daily_report'];
                            $attendance->is_daily_payment = $subValue['is_daily_payment'];
                            $attendance->note = $subValue['note'];
                            $attendance->save();
                        } else {
                            $attendance = new Attendance();
                            $attendance->employee_id = $key;
                            $attendance->base_date = $subKey;
                            $attendance->worktype_id = $subValue['worktype'];
                            $attendance->workplace_id = $subValue['workplace'];
                            $attendance->is_pickup = $subValue['is_pickup'];
                            $attendance->overtime = $subValue['overtime'];
                            $attendance->is_daily_report = $subValue['is_daily_report'];
                            $attendance->is_daily_payment = $subValue['is_daily_payment'];
                            $attendance->note = $subValue['note'];
                            $attendance->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
