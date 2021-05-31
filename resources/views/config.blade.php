@extends('common.format')
@section('title', '編集')
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
@include('common.header')
@section('content')
    @if (session('msg'))
        <script>
            console.log('msg');
            $(function() {
                toastr.success('{{ session('msg') }}');
            });

        </script>
    @endif
    <div class="card w-75 mt-3 mx-auto">
        <div class="card-header">編集</div>
        <div class="card-body">
            {{ Form::open(['url' => '/config/edit', 'files' => false, 'id' => 'formBlock', 'onSubmit' => 'return submitCheck()']) }}
            <div class="row g-3">
                <div class="col">
                    <div class="input-group">
                        <label class="input-group-text">編集種類</label>
                        {{ Form::select('editingType', $editingTypeList, null, ['id' => 'editingType', 'class' => 'form-select form-select-lg', 'onchange' => 'window.editingTypeChange();']) }}
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('script')
    <script>
        function editingTypeChange() {
            blockRemove('1');

            const editingType = document.getElementById('editingType').value;
            if (editingType != "") {
                switch (editingType) {
                    case '1':
                    case '2':
                        lineTypeSelect();
                        break;
                    case '3':
                        deleteSelectCheck();
                        break;
                }
            }
        }

        function lineTypeSelect() {
            const formBlock = document.getElementById('formBlock');

            var parent;
            var child;
            var grandChild;

            var lineType;

            parent = document.createElement('div');
            parent.id = 'lineTypeBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const editingType = document.getElementById('editingType').value;
            const lineTypeLavel = document.createElement('lavel');
            lineTypeLavel.classList.add('input-group-text');
            switch (editingType) {
                case '1':
                    lineTypeLavel.innerHTML = '追加対象系統';
                    break;
                case '2':
                    lineTypeLavel.innerHTML = '変更対象系統';
                    break;
                case '3':
                    lineTypeLavel.innerHTML = '削除対象系統';
                    break;
            }
            grandChild.appendChild(lineTypeLavel);

            const lineTypeSelect = document.createElement('select');
            lineTypeSelect.id = 'lineType';
            lineTypeSelect.classList.add('form-select');
            lineTypeSelect.classList.add('form-select-lg');
            lineTypeSelect.setAttribute('name', 'lineType');
            lineTypeSelect.addEventListener('change', lineTypeChange);
            grandChild.appendChild(lineTypeSelect);

            lineType = document.createElement('option');
            lineType.value = '';
            lineType.innerHTML = '';
            lineTypeSelect.appendChild(lineType);

            const lineTypeList = @json($lineTypeList);
            for (var prop in lineTypeList) {
                lineType = document.createElement('option');
                lineType.value = `${prop}`;
                lineType.innerHTML = `${lineTypeList[prop]}`;
                lineTypeSelect.appendChild(lineType);
            }
        }

        function lineTypeChange() {
            blockRemove('2');

            const editingType = document.getElementById('editingType').value;
            const lineType = document.getElementById('lineType').value;
            if (lineType != "") {
                switch (editingType) {
                    case '1':
                        switch (lineType) {
                            case '1':
                                employeeAdd();
                                break;
                            case '2':
                            case '3':
                            case '4':
                                examAdd();
                                break;
                        }
                        break;
                    case '2':
                    case '3':
                        targetSelect();
                        break;
                }
            }
        }

        function employeeAdd() {
            const formBlock = document.getElementById('formBlock');

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'addContentBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const nameLavel = document.createElement('lavel');
            nameLavel.classList.add('input-group-text');
            nameLavel.innerHTML = '従業員名';
            grandChild.appendChild(nameLavel);

            const nameInput = document.createElement('input');
            nameInput.id = 'name';
            nameInput.classList.add('form-control');
            nameInput.classList.add('form-control-lg');
            nameInput.setAttribute('name', 'name');
            nameInput.placeholder = '例）山田　太郎';
            nameInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(nameInput);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const dailySalaryLavel = document.createElement('lavel');
            dailySalaryLavel.classList.add('input-group-text');
            dailySalaryLavel.innerHTML = '日当';
            grandChild.appendChild(dailySalaryLavel);

            const dailySalaryInput = document.createElement('input');
            dailySalaryInput.id = 'dailySalary';
            dailySalaryInput.classList.add('form-control');
            dailySalaryInput.classList.add('form-control-lg');
            dailySalaryInput.setAttribute('name', 'dailySalary');
            dailySalaryInput.setAttribute('type', 'tel');
            dailySalaryInput.placeholder = '例）10000';
            dailySalaryInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(dailySalaryInput);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const overtimeFeeLavel = document.createElement('lavel');
            overtimeFeeLavel.classList.add('input-group-text');
            overtimeFeeLavel.innerHTML = '残業代（1時間あたり）';
            grandChild.appendChild(overtimeFeeLavel);

            const overtimeFeeInput = document.createElement('input');
            overtimeFeeInput.id = 'overtimeFee';
            overtimeFeeInput.classList.add('form-control');
            overtimeFeeInput.classList.add('form-control-lg');
            overtimeFeeInput.setAttribute('name', 'overtimeFee');
            overtimeFeeInput.setAttribute('type', 'tel');
            overtimeFeeInput.placeholder = '例）1000';
            overtimeFeeInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(overtimeFeeInput);
        }

        function examAdd() {
            const formBlock = document.getElementById('formBlock');
            const lineType = document.getElementById('lineType').value;

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'addContentBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const nameLavel = document.createElement('lavel');
            nameLavel.classList.add('input-group-text');
            switch (lineType) {
                case '2':
                    nameLavel.innerHTML = '現場名';
                    break;
                case '3':
                    nameLavel.innerHTML = 'シフト名';
                    break;
                case '4':
                    nameLavel.innerHTML = '金額';
                    break;
            }
            grandChild.appendChild(nameLavel);

            const nameInput = document.createElement('input');
            switch (lineType) {
                case '2':
                case '3':
                    nameInput.id = 'name';
                    break;
                case '4':
                    nameInput.id = 'price';
                    break;
            }
            nameInput.classList.add('form-control');
            nameInput.classList.add('form-control-lg');
            switch (lineType) {
                case '2':
                case '3':
                    nameInput.setAttribute('name', 'name');
                    break;
                case '4':
                    nameInput.setAttribute('name', 'price');
                    break;
            }

            switch (lineType) {
                case '2':
                    nameInput.placeholder = '例）テスト建設';
                    break;
                case '3':
                    nameInput.placeholder = '例）日勤';
                    break;
                case '4':
                    nameInput.placeholder = '例）1500';
                    break;
            }
            nameInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(nameInput);
        }

        function targetSelect() {
            const formBlock = document.getElementById('formBlock');
            const lineType = document.getElementById('lineType').value;

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'targetBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const targetLavel = document.createElement('lavel');
            targetLavel.classList.add('input-group-text');
            switch (lineType) {
                case '1':
                    targetLavel.innerHTML = '従業員名';
                    break;
                case '2':
                    targetLavel.innerHTML = '現場名';
                    break;
                case '3':
                    targetLavel.innerHTML = 'シフト名';
                    break;
                case '4':
                    targetLavel.innerHTML = '送迎金額';
                    break;
            }
            grandChild.appendChild(targetLavel);

            const targetIdSelect = document.createElement('select');
            targetIdSelect.id = 'targetId';
            targetIdSelect.classList.add('form-select');
            targetIdSelect.classList.add('form-select-lg');
            targetIdSelect.setAttribute('name', 'targetId');
            targetIdSelect.addEventListener('change', targetIdChange);
            grandChild.appendChild(targetIdSelect);

            var targetId;
            targetId = document.createElement('option');
            targetId.value = '';
            targetId.innerHTML = '';
            targetIdSelect.appendChild(targetId);

            var targetIdList;
            switch (lineType) {
                case '1':
                    targetIdList = @json($employeeList);
                    break;

                case '2':
                    targetIdList = @json($workplaceList);
                    break;

                case '3':
                    targetIdList = @json($worktypeList);
                    break;

                case '4':
                    targetIdList = @json($pickupList);
                    break;
            }
            for (var prop in targetIdList) {
                targetId = document.createElement('option');
                targetId.value = `${prop}`;
                targetId.innerHTML = `${targetIdList[prop]}`;
                targetIdSelect.appendChild(targetId);
            }
        }

        function targetIdChange() {
            blockRemove('3');

            const targetId = document.getElementById('targetId').value;
            if (targetId != "") {
                const editingType = document.getElementById('editingType').value;
                switch (editingType) {
                    case '2':
                        targetItemSelect();
                        break;
                    case '3':
                        deleteItemCheck();
                        break;
                }
            }
        }

        function targetItemSelect() {
            const formBlock = document.getElementById('formBlock');

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'targetItemBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const targetItemLavel = document.createElement('lavel');
            targetItemLavel.classList.add('input-group-text');
            targetItemLavel.innerHTML = '編集対象項目';
            grandChild.appendChild(targetItemLavel);

            targetItemSelect = document.createElement('select');
            targetItemSelect.id = 'targetItem';
            targetItemSelect.classList.add('form-select');
            targetItemSelect.classList.add('form-select-lg');
            targetItemSelect.setAttribute('name', 'targetItem');
            targetItemSelect.addEventListener('change', targetItemChange);
            grandChild.appendChild(targetItemSelect);

            var targetItem;
            targetItem = document.createElement('option');
            targetItem.value = '';
            targetItem.innerHTML = '';
            targetItemSelect.appendChild(targetItem);

            const lineType = document.getElementById('lineType').value;
            var targetItemList;
            switch (lineType) {
                case '1':
                    targetItemList = @json($employeeItemList);
                    break;
                case '2':
                    targetItemList = @json($workplaceItemList);
                    break;
                case '3':
                    targetItemList = @json($worktypeItemList);
                    break;
                case '4':
                    targetItemList = @json($pickupItemList);
                    break;
            }
            for (var prop in targetItemList) {
                targetItem = document.createElement('option');
                targetItem.value = `${prop}`;
                targetItem.innerHTML = `${targetItemList[prop]}`;
                targetItemSelect.appendChild(targetItem);
            }
        }

        function targetItemChange() {
            blockRemove('4');

            const lineType = document.getElementById('lineType').value;
            const targetItem = document.getElementById('targetItem').value;
            if (targetItem != "") {
                switch (lineType) {
                    case '1':
                        switch (targetItem) {
                            case '1':
                                editingToName();
                                break;
                            case '2':
                            case '3':
                                editingToPrice();
                                break;
                        }
                        break;
                    case '2':
                    case '3':
                        editingToName();
                        break;
                    case '4':
                        editingToPrice();
                        break;
                }
            }
        }

        function editingToName() {
            const formBlock = document.getElementById('formBlock');
            const lineType = document.getElementById('lineType').value;

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'editBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const nameInputLavel = document.createElement('lavel');
            nameInputLavel.classList.add('input-group-text');
            nameInputLavel.innerHTML = '変更後の名前';
            grandChild.appendChild(nameInputLavel);

            const nameInput = document.createElement('input');
            nameInput.id = 'name';
            nameInput.classList.add('form-control');
            nameInput.classList.add('form-control-lg');
            nameInput.setAttribute('name', 'name');
            switch (lineType) {
                case '1':
                    nameInput.placeholder = '例）山田　太郎';
                    break;
                case '2':
                    nameInput.placeholder = '例）テスト建設';
                    break;
                case '3':
                    nameInput.placeholder = '例）日勤';
                    break;
            }
            nameInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(nameInput);
        }

        function editingToPrice() {
            blockRemove('4');

            const editingType = document.getElementById('editingType').value;
            const lineType = document.getElementById('lineType').value;

            const formBlock = document.getElementById('formBlock');

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'editBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-1');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const priceInputLavel = document.createElement('lavel');
            priceInputLavel.classList.add('input-group-text');
            priceInputLavel.innerHTML = '変更後の金額';
            grandChild.appendChild(priceInputLavel);

            const priceInput = document.createElement('input');
            priceInput.id = 'price';
            priceInput.classList.add('form-control');
            priceInput.classList.add('form-control-lg');
            priceInput.setAttribute('name', 'price');
            priceInput.setAttribute('type', 'tel');
            priceInput.placeholder = '例）10000';

            const targetItem = document.getElementById('targetItem').value;
            const targetId = document.getElementById('targetId').value;
            var priceList;
            switch (lineType) {
                case '1':
                    switch (targetItem) {
                        case '2':
                            priceList = @json($dailySalaryList);
                            break;
                        case '3':
                            priceList = @json($overtimeFeeList);
                            break;
                    }
                    break;
                case '4':
                    switch (targetItem) {
                        case '1':
                            priceList = @json($pickupList);
                            break;
                    }
                    break;
            }
            priceInput.value = `${priceList[targetId]}`;
            priceInput.addEventListener('keyup', inputChange);
            grandChild.appendChild(priceInput);

            if (editingType != '1') {
                if (lineType != '4') {

                    child = document.createElement('div');
                    child.classList.add('col');
                    parent.appendChild(child);

                    grandChild = document.createElement('div');
                    grandChild.classList.add('input-group');
                    child.appendChild(grandChild);

                    var startYear;
                    const nowDate = new Date();
                    const nowYear = nowDate.getFullYear();
                    const nowMonth = nowDate.getMonth() + 1;

                    const startMonthLavel = document.createElement('lavel');
                    startMonthLavel.classList.add('input-group-text');
                    startMonthLavel.innerHTML = '適用開始月';
                    grandChild.appendChild(startMonthLavel);

                    const startYearSelect = document.createElement('select');
                    startYearSelect.id = 'startYear';
                    startYearSelect.classList.add('form-select');
                    startYearSelect.classList.add('form-select-lg');
                    startYearSelect.setAttribute('name', 'year');
                    grandChild.appendChild(startYearSelect);

                    for (let i = nowYear - 3; i <= nowYear + 1; i++) {
                        startYear = document.createElement('option');
                        startYear.value = i;
                        startYear.innerHTML = i;
                        if (i == nowYear) {
                            startYear.setAttribute('selected', 'selected');
                        }
                        startYearSelect.appendChild(startYear);
                    }

                    const YearLavel = document.createElement('lavel');
                    YearLavel.classList.add('input-group-text');
                    YearLavel.innerHTML = '年';
                    grandChild.appendChild(YearLavel);

                    const startMonthSelect = document.createElement('select');
                    startMonthSelect.id = 'startMonth';
                    startMonthSelect.classList.add('form-select');
                    startMonthSelect.classList.add('form-select-lg');
                    startMonthSelect.setAttribute('name', 'month');
                    grandChild.appendChild(startMonthSelect);

                    for (let i = 1; i <= 12; i++) {
                        startMonth = document.createElement('option');
                        startMonth.value = i;
                        startMonth.innerHTML = i;
                        if (i == nowMonth) {
                            startMonth.setAttribute('selected', 'selected');
                        }
                        startMonthSelect.appendChild(startMonth);
                    }

                    const MonthLavel = document.createElement('lavel');
                    MonthLavel.classList.add('input-group-text');
                    MonthLavel.innerHTML = '月';
                    grandChild.appendChild(MonthLavel);
                }
            }
        }

        function inputChange() {
            if (document.getElementById('submitBlock') != null) {
                document.getElementById('submitBlock').remove();
            }

            const lineType = document.getElementById('lineType').value;
            switch (lineType) {
                case '1':
                    employeeCheck();
                    break;

                case '2':
                    workplaceCheck();
                    break;

                case '3':
                    worktypeCheck();
                    break;

                case '4':
                    pickupCheck();
                    break;
            }
        }

        function employeeCheck() {
            const editingType = document.getElementById('editingType').value;

            if (editingType == '1') {
                if (document.getElementById('name').value != "" && document.getElementById('dailySalary').value != "" &&
                    document.getElementById('overtimeFee').value != "") {
                    submitButtonView();
                }
            } else if (editingType == '2') {
                const targetItem = document.getElementById('targetItem').value;
                if (targetItem == '1') {
                    if (document.getElementById('name').value != "") {
                        submitButtonView();
                    }
                } else if (targetItem == '2' || targetItem == '3') {
                    if (document.getElementById('price').value != "" && document.getElementById('startYear').value != "" &&
                        document.getElementById('startMonth').value != "") {
                        submitButtonView();
                    }
                }
            }
        }

        function workplaceCheck() {
            if (document.getElementById('name').value != "") {
                submitButtonView();
            }
        }

        function worktypeCheck() {
            if (document.getElementById('name').value != "") {
                submitButtonView();
            }
        }

        function pickupCheck() {
            if (document.getElementById('price').value != "") {
                submitButtonView();
            }
        }

        function submitButtonView() {
            blockRemove('5');

            const formBlock = document.getElementById('formBlock');

            var parent;
            var child;
            var grandChild;

            parent = document.createElement('div');
            parent.id = 'submitBlock';
            parent.classList.add('row');
            parent.classList.add('g-3');
            parent.classList.add('mt-5');
            formBlock.appendChild(parent);

            child = document.createElement('div');
            child.classList.add('col');
            parent.appendChild(child);

            grandChild = document.createElement('div');
            grandChild.classList.add('input-group');
            child.appendChild(grandChild);

            const submitButton = document.createElement('input')
            submitButton.classList.add('submit');
            submitButton.classList.add('mx-auto');
            submitButton.classList.add('px-5');
            submitButton.classList.add('btn');
            submitButton.classList.add('btn-lg');
            const editingType = document.getElementById('editingType').value;
            switch (editingType) {
                case '1':
                case '2':
                    submitButton.classList.add('btn-outline-secondary');
                    break;
                case '3':
                    submitButton.classList.add('btn-outline-danger');
                    break;
            }
            submitButton.setAttribute('type', 'submit');
            switch (editingType) {
                case '1':
                    submitButton.setAttribute('value', '追加');
                    break;
                case '2':
                    submitButton.setAttribute('value', '変更');
                    break;
                case '3':
                    submitButton.setAttribute('value', '削除');
                    break;
            }
            grandChild.appendChild(submitButton);
        }

        function deleteSelectCheck() {
            if (window.confirm(
                    '編集種類として【削除】が選択されました。\n\n※警告※\n一度削除した内容を元に戻すことはできません。\n削除することに誤りがないか、いま一度ご確認ください。\n\n【削除】を継続しますか？')) {
                lineTypeSelect();
            } else {
                window.alert('【削除】がキャンセルされました。');
                document.getElementById('editingType').selectedIndex = -1;
            }
        }

        function deleteItemCheck() {
            const lineType = document.getElementById('lineType').options[document.getElementById('lineType').selectedIndex]
                .text;
            const targetId = document.getElementById('targetId').options[document.getElementById('targetId').selectedIndex]
                .text;

            if (window.confirm(
                    '編集種類として【削除】が選択されており、下記の内容を削除しようとしています。\n\n' + '削除対象系統：' + lineType + '\n' + '削除対象：' + targetId +
                    '\n\n' + '※警告※\n一度削除した内容を元に戻すことはできません。\n削除することに誤りがないか、いま一度ご確認ください。\n\n【削除】を継続しますか？'
                )) {
                submitButtonView();
            } else {
                window.alert('【削除】がキャンセルされました。');
                document.getElementById('editingType').selectedIndex = -1;
                blockRemove('1');
            }
        }

        function submitCheck() {
            var executionType;
            const editingType = document.getElementById('editingType').value;
            switch (editingType) {
                case '1':
                    executionType = '追加';
                    break;
                case '2':
                    executionType = '変更';
                    break;
                case '3':
                    executionType = '削除';
                    break;
            }

            if (window.confirm(executionType + 'してよろしいですか？')) {
                return true;
            } else {
                window.alert('キャンセルされました');
                return false;
            }
        }

        function blockRemove(num) {
            switch (num) {
                case '1':
                    if (document.getElementById('lineTypeBlock') != null) {
                        document.getElementById('lineTypeBlock').remove();
                    }
                    case '2':
                        if (document.getElementById('addContentBlock') != null) {
                            document.getElementById('addContentBlock').remove();
                        }
                        if (document.getElementById('targetBlock') != null) {
                            document.getElementById('targetBlock').remove();
                        }
                        case '3':
                            if (document.getElementById('targetItemBlock') != null) {
                                document.getElementById('targetItemBlock').remove();
                            }
                            case '4':
                                if (document.getElementById('editBlock') != null) {
                                    document.getElementById('editBlock').remove();
                                }
                                case '5':
                                    if (document.getElementById('submitBlock') != null) {
                                        document.getElementById('submitBlock').remove();
                                    }
                                    break;
            }
        };

    </script>
    <script src="{{ mix('js/fadeout.js') }}"></script>
@endsection
