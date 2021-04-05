<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="container">
    <div class="card mt-3">
        <div class="card-header">追加</div>
        <div class="card-body">
            {{ Form::open(['url' => '/config/edit', 'files' => false]) }}
            {{ Form::hidden('updateType', '1') }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">追加対象種別</label>
                        {{ Form::select('type', $typeList, null, ['class' => 'form-select form-select-lg']) }}
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">追加内容</label>
                        {{ Form::text('addContent', null, ['placeholder' => '追加内容', 'class' => 'form-control form-control-lg']) }}
                    </div>
                </div>
                <div class="col">
                    {{ Form::submit('追加', ['class' => 'submit btn-lg btn-outline-secondary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">修正</div>
        <div class="card-body">

            {{ Form::open(['url' => '/config', 'files' => false]) }}
            {{ Form::hidden('updateType', '2') }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">修正対象種別</label>
                        {{ Form::select('type', $typeList, $fixType, ['class' => 'form-select form-select-lg']) }}
                    </div>
                </div>
                <div class="col">
                    {{ Form::submit('選択', ['class' => 'submit btn-lg btn-outline-secondary']) }}
                </div>
            </div>
            {{ Form::close() }}
            {{ Form::open(['url' => '/config/edit', 'files' => false]) }}
            {{ Form::hidden('updateType', '2') }}
            {{ Form::hidden('type', $fixType) }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">修正対象</label>
                        {{ Form::select('target', $fixItemList, null, ['class' => 'form-select form-select-lg']) }}
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">修正内容</label>
                        {{ Form::text('fixes', null, ['placeholder' => '修正内容', 'class' => 'form-control form-control-lg']) }}
                    </div>
                </div>
                <div class="col">
                    {{ Form::submit('修正', ['class' => 'submit btn-lg btn-outline-secondary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">削除</div>
        <div class="card-body">
            {{ Form::open(['url' => '/config', 'files' => false]) }}
            {{ Form::hidden('updateType', '3') }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">削除対象種別</label>
                        {{ Form::select('type', $typeList, $destroyType, ['class' => 'form-select form-select-lg']) }}
                    </div>
                </div>
                <div class="col">
                    {{ Form::submit('選択', ['class' => 'submit btn-lg btn-outline-secondary']) }}
                </div>
            </div>
            {{ Form::close() }}
            {{ Form::open(['url' => '/config/edit', 'files' => false]) }}
            {{ Form::hidden('updateType', '3') }}
            {{ Form::hidden('type', $destroyType) }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">削除対象</label>
                        {{ Form::select('target', $destroyItemList, null, ['class' => 'form-select form-select-lg']) }}
                    </div>
                </div>
                <div class="col">
                    {{ Form::submit('削除', ['class' => 'submit btn-lg btn-outline-secondary']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
