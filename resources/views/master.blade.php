<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ranked Paper Scissor</title>

    <link href="{{ asset('css/master.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{mix('css/app.css')}}?v={{rand(1, 1000000000)}}">
</head>
<body>
    <div id="app">
        <div class="container-fluid min-vh-100 d-flex flex-column">
            <div class="row nav-bar">
                <div class="col d-flex align-items-center">
                    <h1>Ranked Paper Scissor</h1>
                </div>
                <div class="col d-flex align-items-center justify-content-end">
                    
                </div>
            </div>
            <div class="row flex-grow-1">
                <div class="col-md-2 side-bar p-3">
                    <div class="d-flex flex-column h-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-tertiary btn-normal-game w-100 mb-2">Normal Game</button>
                                <button type="button" class="btn btn-primary btn-ranked-game w-100 mb-2">Ranked Game</button>
                                <button type="button" class="btn btn-secondary btn-custom-game w-100 mb-2">Custom Game</button>
                            </div>
                        </div>
                        <div class="row flex-grow-1 align-items-end ">
                            <div class="col">
                                <a type="button" class="btn btn-primary btn-ranked-game w-30" href="{{route('logout')}}">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 main-content p-2 d-flex align-items-center justify-content-center">
                    @yield('main-content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{mix('/js/app.js')}}?v={{rand(1, 1000000000)}}"></script>
    @yield('script')
</body>
</html>
<script>
    // const app = new Vue({
    //     el: '#app',
    //     data: {
    //         connection: null,
    //         player: null,
    //     },
    //     created: function(){
    //         this.connection = new WebSocket('ws://localhost:5050');

    //         this.connection.onopen = function(event) {                
    //             console.log('Connected...');
    //         };

    //         this.connection.onmessage = function(event) {
    //             var parse = JSON.parse(event.data);
    //             console.log(parse);
    //         };

    //         this.connection.onclose = function(event) {
    //             console.log('pipoca');
    //             console.log(event.data);
    //         }
    //     },
    //     mounted: function(){

    //     },
    //     methods: {

    //     }
    // });
</script>