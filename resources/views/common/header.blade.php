@section('header')
    @if (Route::is('attendance') || Route::is('summary.*'))
        @if (Route::is('attendance'))
            <div class="row g-0 fixed01" style="background-color: #f8fafc">
            @elseif (Route::is('summary.*'))
                <div class="row g-0 fixed01" style="background-color: #f8fafc; height: 37px; overflow: hidden;">
        @endif
        <div class="col-3">
            @if (Route::is('attendance'))
                {{ Form::open(['url' => '/', 'files' => false, 'class' => 'm-0']) }}
            @elseif (Route::is('summary.daily'))
                {{ Form::open(['url' => '/summary/daily', 'files' => false, 'class' => 'm-0']) }}
            @elseif (Route::is('summary.employee'))
                {{ Form::open(['url' => '/summary/employee', 'files' => false, 'class' => 'm-0']) }}
            @endif
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
            @if (Route::is('attendance'))
                <a href="javascript:attendance.submit()" class="btn btn-outline-danger">入力内容を保存する</a>
            @elseif (Route::is('summary.*'))
                <ul class="nav nav-tabs justify-content-center">
                    <li class="nav-item">
                        @if (Route::is('summary.daily'))
                            <a class="nav-link" href="/summary/employee">従業員別</a>
                        @elseif (Route::is('summary.employee'))
                            <a class="nav-link active" aria-current="page" href="#">従業員別</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        @if (Route::is('summary.daily'))
                            <a class="nav-link active" aria-current="page" href="#">日計</a>
                        @elseif (Route::is('summary.employee'))
                            <a class="nav-link" href="/summary/daily">日計</a>
                        @endif
                    </li>
                </ul>
            @endif
        </div>
    @endif
    <div class="col text-end">
        @if (!Route::is('attendance'))
            <a href="/" class="btn btn-primary">出勤簿</a>
        @endif
        @if (!Route::is('summary.*'))
            <a href="/summary/employee" class="btn btn-info">集計</a>
        @endif
        @if (!Route::is('config'))
            <a href="/config" class="btn btn-success">編集</a>
        @endif

        @if (Route::is('attendance') || Route::is('summary.*'))
            @if (Route::is('attendance'))
                {{ Form::open(['url' => '/', 'files' => false, 'name' => 'prev', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.daily'))
                {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'prev', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.employee'))
                {{ Form::open(['url' => '/summary/employee', 'files' => false, 'name' => 'prev', 'style' => 'display:inline;']) }}
            @endif
            @if (Route::is('attendance'))
                {{ Form::hidden('viewY', date('Y', strtotime('-1 day', strtotime($earlyWeek)))) }}
                {{ Form::hidden('viewM', date('m', strtotime('-1 day', strtotime($earlyWeek)))) }}
                {{ Form::hidden('viewD', date('d', strtotime('-1 day', strtotime($earlyWeek)))) }}
            @elseif (!Route::is('summary.*'))
                {{ Form::hidden('viewY', date('Y', strtotime('-1 month', strtotime($viewDate)))) }}
                {{ Form::hidden('viewM', date('m', strtotime('-1 month', strtotime($viewDate)))) }}
                {{ Form::hidden('viewD', date('d', strtotime('-1 month', strtotime($viewDate)))) }}
            @endif
            {{ Form::submit('←', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}

            @if (Route::is('attendance'))
                {{ Form::open(['url' => '/', 'files' => false, 'name' => 'now', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.daily'))
                {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'now', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.employee'))
                {{ Form::open(['url' => '/summary/employee', 'files' => false, 'name' => 'now', 'style' => 'display:inline;']) }}
            @endif
            {{ Form::hidden('viewY', date('Y', strtotime($nowDate))) }}
            {{ Form::hidden('viewM', date('m', strtotime($nowDate))) }}
            {{ Form::hidden('viewD', date('d', strtotime($nowDate))) }}
            @if (Route::is('attendance'))
                {{ Form::submit('今週', ['class' => 'submit btn btn-warning']) }}
            @elseif (Route::is('summary.*'))
                {{ Form::submit('今月', ['class' => 'submit btn btn-warning']) }}
            @endif
            {{ Form::close() }}

            @if (Route::is('attendance'))
                {{ Form::open(['url' => '/', 'files' => false, 'name' => 'next', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.daily'))
                {{ Form::open(['url' => '/summary/daily', 'files' => false, 'name' => 'next', 'style' => 'display:inline;']) }}
            @elseif (Route::is('summary.employee'))
                {{ Form::open(['url' => '/summary/employee', 'files' => false, 'name' => 'next', 'style' => 'display:inline;']) }}
            @endif
            @if (Route::is('attendance'))
                {{ Form::hidden('viewY', date('Y', strtotime('+1 day', strtotime($weekend)))) }}
                {{ Form::hidden('viewM', date('m', strtotime('+1 day', strtotime($weekend)))) }}
                {{ Form::hidden('viewD', date('d', strtotime('+1 day', strtotime($weekend)))) }}
            @elseif (Route::is('summary.*'))
                {{ Form::hidden('viewY', date('Y', strtotime('+1 month', strtotime($viewDate)))) }}
                {{ Form::hidden('viewM', date('m', strtotime('+1 month', strtotime($viewDate)))) }}
                {{ Form::hidden('viewD', date('d', strtotime('+1 month', strtotime($viewDate)))) }}
            @endif
            {{ Form::submit('→', ['class' => 'submit btn btn-warning']) }}
            {{ Form::close() }}
        @endif
    </div>
    @if (Route::is('attendance') || Route::is('summary.*'))
        </div>
    @endif
@endsection
