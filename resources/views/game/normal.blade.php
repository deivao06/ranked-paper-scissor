@extends('master')

@section('main-content')
<div id="app-normal" class="d-flex align-items-center justify-content-center h-100">
    @section('sidebar')
        <div class="d-flex flex-column h-100">
            <div class="row">
                <div class="col">
                    
                </div>
            </div>
            <div class="row flex-grow-1 align-items-end ">
                
            </div>
        </div>
    @endsection
    <div v-if="searchingMatch" class="d-flex flex-column justify-content-center align-items-center search-loader">
        <h3>Searching Match...</h3>
        <div class="lds-ripple mb-3"><div></div><div></div></div>
        <a href="{{route('master')}}" class="btn btn-primary">Cancel</a>
    </div>
    <div v-else-if="searchingPlayers" class="d-flex flex-column justify-content-center align-items-center search-loader">
        <h3>Looking for players...</h3>
        <div class="lds-ripple mb-3"><div></div><div></div></div>
        <a href="{{route('master')}}" class="btn btn-primary">Cancel</a>
    </div>
    <div v-else class="w-100 h-100 ps-3 pt-1">
        Match
    </div>
</div>
@endsection

@section('script')
<script>
    const app = new Vue({
        el: '#app-normal',
        data: {
            connection: null,
            playerData: {
                email: "{{Auth::user()->email}}",
                queue: 'normal',
            },
            searchingMatch: true,
            searchingPlayers: false,
            room: null
        },
        created: function(){
            this.connection = new WebSocket('ws://172.22.50.18:5050');

            this.connection.onopen = function(event) {      
                var data = {
                    ...app.playerData,
                    command: 'queue'
                }
                
                app.searchingMatch = true;
                app.connection.send(JSON.stringify(data));
            };

            this.connection.onmessage = function(event) {
                var parse = JSON.parse(event.data);
                switch(parse.command){
                    case 'exit-room':
                        window.location.href = "{{ route('master')}}";
                        break;
                    default:
                    app.room = parse.room;
                    app.searchingMatch = false;

                    if(app.room.players.length < 2){
                        app.searchingPlayers = true;
                    }else{
                        app.searchingPlayers = false;
                    }   
                }
                             
            };
            this.connection.onclose = function(event) {
                console.log(event.data);
            }
        },
        mounted: function(){

        },
        methods: {

        }
    });
</script>
@endsection