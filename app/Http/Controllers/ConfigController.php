<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;
use App\Models\Employee;
use App\Models\Workplace;
use App\Models\Worktype;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typeList = Config::selectTypeList();
        $type = null;

        $fixType = null;
        $destroyType = null;

        $fixItemList = array();
        $destroyItemList = array();

        return view('config', compact('typeList', 'type', 'fixType', 'destroyType', 'fixItemList', 'destroyItemList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        switch ($request->type) {
            case '1':
                $displayOrder = Employee::DisplayOrderMax();
                $displayOrder = $displayOrder + 1;

                $employee = new Employee();
                $employee->name = $request->addContent;
                $employee->display_order = $displayOrder;
                $employee->save();
                break;
            case '2':
                $workplace = new Workplace();
                $workplace->name = $request->addContent;
                $workplace->save();
                break;
            case '3':
                $worktype = new Worktype();
                $worktype->name = $request->addContent;
                $worktype->save();
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
        $typeList = Config::selectTypeList();

        $fixType = null;
        $destroyType = null;

        $fixItemList = array();
        $destroyItemList = array();

        $itemList = array();
        switch ($request->type) {
            case '1':
                $result = Employee::OrderIdAsc()->get();
                foreach ($result as $employee) {
                    $itemList += array($employee->id => $employee->name);
                }
                break;
            case '2':
                $result = Workplace::OrderIdAsc()->get();
                foreach ($result as $workplace) {
                    $itemList += array($workplace->id => $workplace->name);
                }
                break;
            case '3':
                $result = Worktype::OrderIdAsc()->get();
                foreach ($result as $worktype) {
                    $itemList += array($worktype->id => $worktype->name);
                }
                break;
            default:
                break;
        }

        switch ($request->updateType) {
            case '2':
                $fixType = $request->type;
                $fixItemList = $itemList;
                break;
            case '3':
                $destroyType = $request->type;
                $destroyItemList = $itemList;
                break;
            default:
                break;
        }

        return view('config', compact('typeList', 'fixType', 'destroyType', 'fixItemList', 'destroyItemList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $typeList = Config::selectTypeList();
        $fixType = null;
        $destroyType = null;

        $fixItemList = array();
        $destroyItemList = array();

        switch ($request->updateType) {
            case '1':
                $this->create($request);
                break;
            case '2':
                $this->update($request);
                break;
            case '3':
                $this->destroy($request);
                break;
        }

        return view('config', compact('typeList', 'fixType', 'destroyType', 'fixItemList', 'destroyItemList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        switch ($request->type) {
            case '1':
                $employee = Employee::IdAt($request->target)->first();
                $employee->id = $request->target;
                $employee->name = $request->fixes;
                $employee->save();
                break;
            case '2':
                $workplace = Workplace::IdAt($request->target)->first();
                $workplace->id = $request->target;
                $workplace->name = $request->fixes;
                $workplace->save();
                break;
            case '3':
                $worktype = Worktype::IdAt($request->target)->first();
                $worktype->id = $request->target;
                $worktype->name = $request->fixes;
                $worktype->save();
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
        switch ($request->type) {
            case '1':
                $employee = Employee::IdAt($request->target)->first();
                $employee->id = $request->target;
                $employee->delete();
                break;
            case '2':
                $workplace = Workplace::IdAt($request->target)->first();
                $workplace->id = $request->target;
                $workplace->name = $request->fixes;
                $workplace->delete();
                break;
            case '3':
                $worktype = Worktype::IdAt($request->target)->first();
                $worktype->id = $request->target;
                $worktype->delete();
                break;
        }
    }
}
