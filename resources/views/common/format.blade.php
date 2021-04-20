<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')｜Kizuna Attendance System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxxl g-0">
        @yield('header')
        @yield('content')
    </div>
</body>

</html>
