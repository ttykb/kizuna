@extends('common.format')
@section('title', '出勤簿')
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
@include('common.header')
@section('content')
    @if (session('msg'))
        <script>
            $(function() {
                toastr.success('{{ session('msg') }}');
            });

        </script>
    @endif
    {{ Form::open(['url' => '/edit', 'files' => false, 'name' => 'attendance']) }}
    <table class="table table-striped table-bordered text-nowrap">
        <thead class="table-dark table-bordered" style="background-color:#343a40">
            <tr>
                <th scope="col" class="align-middle fixed-column">氏名</th>
                @foreach ($dayList as $base_date)
                    <th scope="col" class="align-middle fixed-column">
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
                                            <td colspan="3">
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[workplace]', $workplaceList, $attendances[$employee->id][$base_date]['workplace'], ['style' => 'width:190px;']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(235, 241, 222);">
                                                送迎
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[pickup]', $pickupList, $attendances[$employee->id][$base_date]['pickup'], ['style' => 'width:70px;']) }}
                                            </td>
                                            <td style="background-color: rgb(221, 217, 196);">
                                                シフト
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[worktype]', $worktypeList, $attendances[$employee->id][$base_date]['worktype'], ['style' => 'width:70px;']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                残業
                                            </td>
                                            <td>
                                                @if (is_null($attendances[$employee->id][$base_date]['overtime']))
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', null, ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:70px;']) }}
                                                @else
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', date('G:i', strtotime($attendances[$employee->id][$base_date]['overtime'])), ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:70px;']) }}
                                                @endif
                                            </td>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                手当
                                            </td>
                                            <td>
                                                @if (is_null($attendances[$employee->id][$base_date]['allowance']))
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[allowance]', null, ['class' => 'form-control', 'id' => 'allowance', 'style' => 'width:70px;']) }}
                                                @else
                                                    {{ Form::text($employee->id . '[' . $base_date . ']' . '[allowance]', $attendances[$employee->id][$base_date]['allowance'], ['class' => 'form-control', 'id' => 'allowance', 'style' => 'width:70px;']) }}
                                                @endif
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
                                            <td style="background-color: rgb(228, 223, 236);">
                                                日払い
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', '1', $attendances[$employee->id][$base_date]['is_daily_payment']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[note]', $attendances[$employee->id][$base_date]['note'], ['class' => 'form-control', 'id' => 'note', 'placeholder' => '備考']) }}
                                            </td>
                                        </tr>
                                    @else
                                        {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[id]', null) }}
                                        <tr>
                                            <td style="background-color: rgb(242, 220, 219);">
                                                現場
                                            </td>
                                            <td colspan="3">
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[workplace]', $workplaceList, null, ['style' => 'width:190px;']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(235, 241, 222);">
                                                送迎
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[pickup]', $pickupList, null, ['style' => 'width:70px;']) }}
                                            </td>
                                            <td style="background-color: rgb(221, 217, 196);">
                                                シフト
                                            </td>
                                            <td>
                                                {{ Form::select($employee->id . '[' . $base_date . ']' . '[worktype]', $worktypeList, null, ['style' => 'width:70px;']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                残業
                                            </td>
                                            <td>
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[overtime]', null, ['class' => 'form-control', 'placeholder' => '--:--', 'style' => 'width:70px;']) }}
                                            </td>
                                            <td style="background-color: rgb(218, 238, 243);">
                                                手当
                                            </td>
                                            <td>
                                                {{ Form::text($employee->id . '[' . $base_date . ']' . '[allowance]', null, ['class' => 'form-control', 'id' => 'allowance', 'style' => 'width:70px;']) }}
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
                                            <td style="background-color: rgb(228, 223, 236);">
                                                日払い
                                            </td>
                                            <td class="text-center">
                                                {{ Form::hidden($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', null) }}
                                                {{ Form::checkbox($employee->id . '[' . $base_date . ']' . '[is_daily_payment]', '1') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
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
@endsection
