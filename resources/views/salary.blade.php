@extends('common.format')
@section('title', '給与計算')
    @include('common.header')
@section('content')
        <table class="table table-relative table-striped table-hover table-bordered text-nowrap">
            <thead class="table-dark table-bordered">
                <tr>
                    <th scope="col" class="align-middle fixed-column">
                        氏名
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        日当合計
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        残業合計
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        手当合計
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        送迎合計
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        合計
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        日払い済み
                    </th>
                    <th scope="col" class="align-middle fixed-column">
                        差引支給額
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <th scope="row" class="align-top">
                            {{ $employee->name }}
                        </th>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('daily_salaly', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['daily_salaly']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('overtime', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['overtime']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('allowance', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['allowance']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('pickup', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['pickup']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('total', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['total']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('dailyPayment', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['dailyPayment']) }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists($employee->id, $salaryArray) && array_key_exists('total', $salaryArray[$employee->id]) && array_key_exists('dailyPayment', $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['total'] - $salaryArray[$employee->id]['dailyPayment']) }}
                            @elseif (array_key_exists($employee->id, $salaryArray) && array_key_exists('total',
                                $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['total']) }}
                            @elseif (array_key_exists($employee->id, $salaryArray) && array_key_exists('dailyPayment',
                                $salaryArray[$employee->id]))
                                {{ number_format($salaryArray[$employee->id]['dailyPayment']) }}
                            @else
                                0
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
