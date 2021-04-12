<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="container-xxxl">
    <div class="row g-0 fixed01" style="background-color: #f8fafc; height: 37px; overflow: hidden;">
        <div class="col-3">
            {{ Form::open(['url' => '/summary/daily', 'files' => false, 'class' => 'm-0']) }}
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
            <ul class="nav nav-tabs justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="/summary/employee">従業員別</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link active" aria-current="page" href="#">日計</a>
                </li>
            </ul>
        </div>

        <div class="col text-end">
            <a href="/" class="btn btn-primary">出勤簿</a>
            <a href="config" class="btn btn-success">編集</a>

            {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'prev', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime('-1 month', strtotime($viewDate)))) }}
            {{ Form::hidden('viewM', date('m', strtotime('-1 month', strtotime($viewDate)))) }}
            {{ Form::hidden('viewD', date('d', strtotime('-1 month', strtotime($viewDate)))) }}
            {{ Form::submit('←', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}

            {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'now', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime($nowDate))) }}
            {{ Form::hidden('viewM', date('m', strtotime($nowDate))) }}
            {{ Form::hidden('viewD', date('d', strtotime($nowDate))) }}
            {{ Form::submit('今月', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}

            {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'next', 'style' => 'display:inline;']) }}
            {{ Form::hidden('viewY', date('Y', strtotime('+1 month', strtotime($viewDate)))) }}
            {{ Form::hidden('viewM', date('m', strtotime('+1 month', strtotime($viewDate)))) }}
            {{ Form::hidden('viewD', date('d', strtotime('+1 month', strtotime($viewDate)))) }}
            {{ Form::submit('→', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}
        </div>
    </div>

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
                            {{ $summaryArray[$day]['is_pickup'] }}
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
                        {{ $summaryArray['total']['is_pickup'] }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
