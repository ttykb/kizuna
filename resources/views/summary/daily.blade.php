@extends('common.format')
@section('title', '集計／日計')
@include('common.header')
@section('content')
    <table class="table table-striped table-bordered text-nowrap">
        <thead class="table-dark table-bordered" style="background-color:#343a40">
            <tr>
                <th scope="col" class="align-top fixed02">日付</th>
                @foreach ($workplaceList as $workplace)
                    <th scope="col" class="fixed02">
                        {{ $workplace }}
                    </th>
                @endforeach
                <th scope="col" class="fixed02">
                    合計
                </th>
                <th scope="col" class="fixed02">
                    日報数
                </th>
                <th scope="col" class="fixed02">
                    残業
                </th>
                <th scope="col" class="fixed02">
                    送迎
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dayList as $day)
                <tr>
                    <th scope="row" class="align-top">
                        {{ $day }}
                    </th>
                    @foreach ($workplaceList as $workplaceKey => $workplaceValue)
                        <td>
                            @if (array_key_exists($day, $summaryArray) && array_key_exists($workplaceKey, $summaryArray[$day]))
                                {{ $summaryArray[$day][$workplaceKey] }}
                            @else
                                0
                            @endif
                        </td>
                    @endforeach
                    <td style="background-color: rgb(226, 239, 218);">
                        @if (array_key_exists($day, $summaryArray))
                            {{ $summaryArray[$day]['total_count'] }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($day, $summaryArray))
                            {{ $summaryArray[$day]['is_daily_report'] }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($day, $summaryArray))
                            {{ substr($summaryArray[$day]['overtime'], 0, -3) }}
                        @endif
                    </td>
                    <td>
                        @if (array_key_exists($day, $summaryArray))
                            {{ $summaryArray[$day]['pickup'] }}
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
                        {{ $summaryArray['total']['total_count'] }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if (array_key_exists('total', $summaryArray))
                        {{ $summaryArray['total']['is_daily_report'] }}
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
