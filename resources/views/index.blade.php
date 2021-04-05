<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="container-xxxl">
    <div class="row fixed01 background-color: #f8fafc">
        <div class="col-3">
            {{ Form::open(['url' => '/', 'files' => false, 'class' => 'm-0']) }}
            {{ Form::hidden('nowDate', $nowDate) }}
            <div class="input-group">
                {{ Form::select('viewY', App\Models\BaseDate::selectYearList($nowDate), $viewY, ['id' => 'viewY', 'class' => 'form-select']) }}
                <label class="input-group-text" for="viewY">年</label>
                {{ Form::select('viewM', App\Models\BaseDate::selectMonthList(), $viewM, ['id' => 'viewM', 'class' => 'form-select']) }}
                <label class="input-group-text" for="viewM">月</label>
                {{ Form::hidden('viewD', '01') }}
                {{ Form::submit('表示', ['class' => 'submit btn btn-outline-secondary']) }}
            </div>
            {{ Form::close() }}
        </div>
        <div class="col text-center">
            <a href="javascript:attendance.submit()" class="btn btn-outline-danger">入力内容を保存する</a>
        </div>
        <div class="col text-end">
            <a href="config" class="btn btn-success">編集</a>

            {{ Form::open(['url' => '/', 'files' => false, 'name' => 'prev', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime('-1 day', strtotime($earlyWeek)))) }}
            {{ Form::hidden('viewM', date('m', strtotime('-1 day', strtotime($earlyWeek)))) }}
            {{ Form::hidden('viewD', date('d', strtotime('-1 day', strtotime($earlyWeek)))) }}
            {{ Form::submit('←', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}

            {{ Form::open(['url' => '/', 'files' => false, 'name' => 'now', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime($nowDate))) }}
            {{ Form::hidden('viewM', date('m', strtotime($nowDate))) }}
            {{ Form::hidden('viewD', date('d', strtotime($nowDate))) }}
            {{ Form::submit('今週', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}

            {{ Form::open(['url' => '/', 'files' => false, 'name' => 'next', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime('+1 day', strtotime($weekend)))) }}
            {{ Form::hidden('viewM', date('m', strtotime('+1 day', strtotime($weekend)))) }}
            {{ Form::hidden('viewD', date('d', strtotime('+1 day', strtotime($weekend)))) }}
            {{ Form::submit('→', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}
        </div>
    </div>
    {{ Form::open(['url' => '/edit', 'files' => false, 'name' => 'attendance']) }}
    <table class="table table-striped table-bordered text-nowrap">
        <thead class="table-dark table-bordered" style="background-color:#343a40">
            <tr>
                <th scope="col" class="align-top fixed02">氏名</th>
                @foreach ($dayList as $base_date)
                    <th scope="col" class="fixed02">
                        {{ date('m/d', strtotime($base_date)) . '(' . App\Models\DayOfTheWeek::viewDayOfTheWeek($base_date) . ')' }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <th scope="row" class="align-top">
                        {{ $employee->name }}
                    </th>
                    @foreach ($dayList as $base_date)
                        <td>
                            <table class="table-bordered text-nowrap">
                                <tbody>
                                    @if (array_key_exists($employee->id, $attendances) && array_key_exists($base_date, $attendances[$employee->id]))
                                        {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[id]', $attendances[$employee->id][$base_date]['id']) }}
                                        <tr>
                                            <td style="background-color: rgb(242, 220, 219);">
                                                現場
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[workplace]', $workplaceList, $attendances[$employee->id][$base_date]['workplace'], ['style' => 'width:110px;']) }}
                                            </td>
                                            <td style="background-color: rgb(221, 217, 196);">
                                                シフト
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(235, 241, 222);">
                                                送迎
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[is_pickup]', App\Models\Pickup::selectList(), $attendances[$employee->id][$base_date]['is_pickup']) }}
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[worktype]', $worktypeList, $attendances[$employee->id][$base_date]['worktype']) }}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                残業
                                            </td>
                                            <td>
                                                @if (is_null($attendances[$employee->id][$base_date]['overtime']))
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', null, ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:110px;']) }}
                                                @else
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', date('G:i', strtotime($attendances[$employee->id][$base_date]['overtime'])), ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:110px;']) }}
                                                @endif
                                            </td>
                                            <td style="background-color: rgb(228, 223, 236);">
                                                日払い
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(253, 233, 217);">
                                                日報
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_report]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_report]', '1', $attendances[$employee->id][$base_date]['is_daily_report']) }}
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', '1', $attendances[$employee->id][$base_date]['is_daily_payment']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[note]', $attendances[$employee->id][$base_date]['note'], ['class' => 'form-control', 'id' => 'note', 'placeholder' => '備考']) }}
                                            </td>
                                        </tr>
                                    @else
                                        {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[id]', null) }}
                                        <tr>
                                            <td style="background-color: rgb(242, 220, 219);">
                                                現場
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[workplace]', $workplaceList, null, ['style' => 'width:110px;']) }}
                                            </td>
                                            <td style="background-color: rgb(221, 217, 196);">
                                                シフト
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(235, 241, 222);">
                                                送迎
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[is_pickup]', App\Models\Pickup::selectList()) }}
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[worktype]', $worktypeList) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                残業
                                            </td>
                                            <td>
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', null, ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:110px;']) }}
                                            </td>
                                            <td style="background-color: rgb(228, 223, 236);">
                                                日払い
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(253, 233, 217);">
                                                日報
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_report]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_report]', '1') }}
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', '1') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[note]', null, ['class' => 'form-control', 'placeholder' => '備考']) }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ Form::close() }}
</div>
