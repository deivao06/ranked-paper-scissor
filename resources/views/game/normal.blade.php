@extends('master')

@section('sidebar')
    <div id="app-sidebar" class="h-100">
        <div v-if="show" class="d-flex flex-column h-100">
            <div class="row h-50">
                <div class="col">
                    <div class="row pb-2">
                        <div class="col">
                            <span>@{{player2.info.name}}</span>
                        </div>
                        <div class="col text-end">
                            <span>@{{player2.state}}</span>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="card player-choice">
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row h-50">
                <div class="col">
                    <div class="row pb-2">
                        <div class="col">
                            <span>@{{player1.info.name}}</span>
                        </div>
                        <div class="col text-end">
                            <span>@{{player1.state}}</span>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="card player-choice">
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
<div id="app-normal" class="w-100 h-100 d-flex">
    <div v-if="searchingMatch" class="d-flex w-100 flex-column justify-content-center align-items-center search-loader">
        <h3>Searching Match...</h3>
        <div class="lds-ripple mb-3"><div></div><div></div></div>
        <a href="{{route('master')}}" class="btn btn-primary">Cancel</a>
    </div>
    <div v-else-if="searchingPlayers" class="d-flex w-100 flex-column justify-content-center align-items-center search-loader">
        <h3>Looking for players...</h3>
        <div class="lds-ripple mb-3"><div></div><div></div></div>
        <a href="{{route('master')}}" class="btn btn-primary">Cancel</a>
    </div>
    <div v-else class="d-flex w-100 flex-column align-items-center justify-content-center">
        <div class="row mb-3">
            <h3>Choose your option..</h3>
        </div>
        <div class="row mb-5">
            <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'rock' ? 'btn-primary' : 'btn-secondary'" @click="game.player1.choice = 'rock'">Rock</button></div>
            <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'paper' ? 'btn-primary' : 'btn-secondary'" @click="game.player1.choice = 'paper'">Paper</button></div>
            <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'scissor' ? 'btn-primary' : 'btn-secondary'" @click="game.player1.choice = 'scissor'">Scissor</button></div>
        </div>
        <div class="row">
            <div class="col"><button class="btn btn-tertiary" :disabled="game.player1.choice == null" @click="endTurn()">End Turn</button></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const sidebar = new Vue({
        el: "#app-sidebar",
        data: {
            show: false,
            player1: null,
            player2: null,
        }
    })

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
            room: null,
            game: null,
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
                        window.location.href = "{{route('master')}}";
                        break;
                    case 'game-started':
                        app.game = parse.game;
                        app.searchingPlayers = false;
                        break;
                    default:
                        app.room = parse.room;
                        app.searchingMatch = false;

                        if(app.room.players.length < 2){
                            app.searchingPlayers = true;
                        }else{
                            var data = {
                                command: 'start-game'
                            }

                            app.connection.send(JSON.stringify(data));
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
            endTurn: function(){
                var data = {
                    game: this.game,
                    command: 'end-turn'
                }
                
                app.connection.send(JSON.stringify(data));
            }
        },
        watch: {
            game: function(newValue, oldValue){
                if(newValue.player1.info.email == app.playerData.email){
                    sidebar.player1 = newValue.player1;
                    sidebar.player2 = newValue.player2;
                }else{
                    sidebar.player1 = newValue.player2;
                    sidebar.player2 = newValue.player1;

                    app.game.player1 = newValue.player2;
                    app.game.player2 = newValue.player1;
                }

                sidebar.show = true;
            }
        },
    });
</script>
@endsection