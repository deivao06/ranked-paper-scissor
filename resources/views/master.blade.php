<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ranked Paper Scissor</title>

    <link href="{{ asset('css/master.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{mix('css/app.css')}}?v={{rand(1, 1000000000)}}">
    <script src="{{mix('/js/app.js')}}?v={{rand(1, 1000000000)}}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Erica+One&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="container-fluid min-vh-100 d-flex flex-column">
            <div class="row nav-bar">
                <div class="col-7 d-flex align-items-center">
                    <a class="primary-link" href="{{route('master')}}"><h2>Ranked Paper Scissor</h2></a>
                </div>
                <div class="col-5 d-flex align-items-center justify-content-end">
                    {{Auth::user()->name}}
                </div>
            </div>
            <div class="row flex-grow-1">
                <div class="col-md-2 p-3">
                    @yield('sidebar')
                </div>
                <div class="col-md-10 main-content">
                    @yield('main-content')
                </div>
            </div>
        </div>
    </div>
    @yield('script')
</body>
</html>