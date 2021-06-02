@extends('common.format')
@section('title', '集計／従業員別')
@include('common.header')
@section('content')
    <table class="table table-relative table-striped table-bordered text-nowrap">
        <thead class="table-dark table-bordered" style="background-color:#343a40">
            <tr>
                <th scope="col" class="align-middle fixed-column">氏名</th>
                @foreach ($workplaceList as $workplace)
                    <th scope="col" class="align-middle fixed-column">
                        {{ $workplace }}
                    </th>
                @endforeach
                <th scope="col" class="align-middle fixed-column">
                    合計
                </th>
                <th scope="col" class="align-middle fixed-column">
                    日報数
                </th>
                <th scope="col" class="align-middle fixed-column">
                    残業
                </th>
                <th scope="col" class="align-middle fixed-column">
                    送迎
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <th scope="row" class="align-top">
                        {{ $employee->name }}
                    </th>
                    @foreach ($workplaceList as $workplaceKey => $workplaceValue)
                        <td>
                            @if (array_key_exists($employee->id, $summaryArray) && array_key_exists($workplaceKey, $summaryArray[$employee->id]))
                                {{ $summaryArray[$employee->id][$workplaceKey] }}
                            @else
                                0
                            @endif
                        </td>
                    @endforeach
                    <td style="background-color: rgb(226, 239, 218);">
                        @if (array_key_exists($employee->id, $summaryArray))
                            {{ $summaryArray[$employee->id]['total_count'] }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($employee->id, $summaryArray))
                            {{ $summaryArray[$employee->id]['is_daily_report'] }}
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($employee->id, $summaryArray))
                            {{ substr($summaryArray[$employee->id]['overtime'], 0, -3) }}
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($employee->id, $summaryArray))
                            {{ $summaryArray[$employee->id]['pickup'] }}
                        @else
                            0
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="table-primary">
                <th scope="row" class="align-top">
                    合計
                </th>
                @foreach ($workplaceList as $workplaceKey => $workplaceValue)
                    <td>
                        @if (array_key_exists('total', $summaryArray) && array_key_exists($workplaceKey, $summaryArray['total']))
                            {{ $summaryArray['total'][$workplaceKey] }}
                        @else
                            0
                        @endif
                    </td>
                @endforeach
                <td>
                    @if (array_key_exists('total', $summaryArray))
                        {{ $summaryArray['total']['is_daily_report'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (array_key_exists('total', $summaryArray))
                        {{ $summaryArray['total']['total_count'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (array_key_exists('total', $summaryArray))
                        {{ substr($summaryArray['total']['overtime'], 0, -3) }}
                    @endif
                </td>
                <td>
                    @if (array_key_exists('total', $summaryArray))
                        {{ $summaryArray['total']['pickup'] }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @endsection
