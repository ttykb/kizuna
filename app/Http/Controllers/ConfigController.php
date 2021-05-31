<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Employee;
use App\Models\DailySalary;
use App\Models\OvertimeFee;
use App\Models\Pickup;
use App\Models\Workplace;
use App\Models\Worktype;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editingTypeList = Config::editingTypeList();
        $lineTypeList = Config::lineTypeList();

        $employeeItemList = Config::employeeItemList();
        $dailySalaryItemList = Config::dailySalaryItemList();
        $overtimeFeeItemList = Config::overtimeFeeItemList();

        $workplaceItemList = Config::workplaceItemList();
        $worktypeItemList = Config::worktypeItemList();
        $pickupItemList = Config::pickupItemList();

        $employeeList = array();
        $result = Employee::OrderIdAsc()->get();
        foreach ($result as $employee) {
            $employeeList += array($employee->id => $employee->name);
        }

        $dailySalaryList = array();
        $result = DB::table('daily_salaries as T1')
            ->whereRaw('not exists (select * from daily_salaries T2 where T1.employee_id = T2.employee_id And T1.app_start_date < T2.app_start_date)')
            ->orderBy('employee_id', 'asc')
            ->get();
        foreach ($result as $dailySalary) {
            $dailySalaryList += array($dailySalary->employee_id => $dailySalary->price);
        }

        $overtimeFeeList = array();
        $result = DB::table('overtime_fees as T1')
            ->whereRaw('not exists (select * from overtime_fees T2 where T1.employee_id = T2.employee_id And T1.app_start_date < T2.app_start_date)')
            ->orderBy('employee_id', 'asc')
            ->get();
        foreach ($result as $overtimeFee) {
            $overtimeFeeList += array($overtimeFee->employee_id => $overtimeFee->price);
        }

        $workplaceList = array();
        $result = Workplace::OrderIdAsc()->get();
        foreach ($result as $workplace) {
            $workplaceList += array($workplace->id => $workplace->name);
        }

        $worktypeList = array();
        $result = Worktype::OrderIdAsc()->get();
        foreach ($result as $worktype) {
            $worktypeList += array($worktype->id => $worktype->name);
        }

        $pickupList = array();
        $result = Pickup::OrderIdAsc()->get();
        foreach ($result as $pickup) {
            $pickupList += array($pickup->id => $pickup->price);
        }

        session()->keep('msg');
        return view('config', compact('editingTypeList', 'lineTypeList', 'employeeItemList', 'dailySalaryItemList', 'overtimeFeeItemList', 'workplaceItemList', 'worktypeItemList', 'pickupItemList', 'employeeList', 'dailySalaryList', 'overtimeFeeList', 'workplaceList', 'worktypeList', 'pickupList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        switch ($request->lineType) {
            case '1':
                $displayOrder = Employee::DisplayOrderMax();
                $displayOrder = $displayOrder + 1;

                $employee = new Employee();
                $employee->name = $request->name;
                $employee->display_order = $displayOrder;
                $employee->save();

                $employee_id = $employee->id;

                $dailySalary = new DailySalary();
                $dailySalary->employee_id = $employee_id;
                $dailySalary->price = $request->dailySalary;
                $dailySalary->save();

                $overtimeFee = new OvertimeFee();
                $overtimeFee->employee_id = $employee_id;
                $overtimeFee->price = $request->overtimeFee;
                $overtimeFee->save();
                break;
            case '2':
                $workplace = new Workplace();
                $workplace->name = $request->name;
                $workplace->save();
                break;
            case '3':
                $worktype = new Worktype();
                $worktype->name = $request->name;
                $worktype->save();
                break;
            case '4':
                $pickup = new Pickup();
                $pickup->price = $request->price;
                $pickup->save();
                break;
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        switch ($request->editingType) {
            case '1':
                $this->create($request);
                session()->flash('msg', '追加が完了しました');
                break;
            case '2':
                $this->update($request);
                session()->flash('msg', '変更が完了しました');
                break;
            case '3':
                $this->destroy($request);
                session()->flash('msg', '削除が完了しました');
                break;
        }

        // リダイレクト処理
        return redirect('/config');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        switch ($request->lineType) {
            case '1':
                switch ($request->targetItem) {
                    case '1':
                        $employee = Employee::IdAt($request->targetId)->first();
                        $employee->id = $request->targetId;
                        $employee->name = $request->name;
                        $employee->save();
                        break;
                    case '2':
                        $appStartDate = $request->year . '-' . $request->month . '-01';
                        $dailySalary = new dailySalary();
                        $dailySalary->employee_id = $request->targetId;
                        $dailySalary->price = $request->price;
                        $dailySalary->app_start_date = $appStartDate;
                        $dailySalary->save();
                        break;
                    case '3':
                        $appStartDate = $request->year . '-' . $request->month . '-01';
                        $overtimeFee = new overtimeFee();
                        $overtimeFee->employee_id = $request->targetId;
                        $overtimeFee->price = $request->price;
                        $overtimeFee->app_start_date = $appStartDate;
                        $overtimeFee->save();
                        break;
                }
                break;
            case '2':
                switch ($request->targetItem) {
                    case '1':
                        $workplace = Workplace::IdAt($request->targetId)->first();
                        $workplace->id = $request->targetId;
                        $workplace->name = $request->name;
                        $workplace->save();
                        break;
                }
                break;
            case '3':
                switch ($request->targetItem) {
                    case '1':
                        $worktype = Worktype::IdAt($request->targetId)->first();
                        $worktype->id = $request->target;
                        $worktype->name = $request->name;
                        $worktype->save();
                        break;
                }
                break;
            case '4':
                switch ($request->targetItem) {
                    case '1':
                        $pickup = Pickup::IdAt($request->targetId)->first();
                        $pickup->id = $request->targetId;
                        $pickup->price = $request->price;
                        $pickup->save();
                        break;
                }
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        switch ($request->lineType) {
            case '1':
                $employee = Employee::IdAt($request->targetId)->first();
                $employee->id = $request->targetId;
                $employee->delete();
                break;
            case '2':
                $workplace = Workplace::IdAt($request->targetId)->first();
                $workplace->id = $request->targetId;
                $workplace->delete();
                break;
            case '3':
                $worktype = Worktype::IdAt($request->targetId)->first();
                $worktype->id = $request->targetId;
                $worktype->delete();
                break;
            case '4':
                $pickup = Pickup::IdAt($request->targetId)->first();
                $pickup->id = $request->targetId;
                $pickup->delete();
                break;
        }
    }
}
