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
                            <span :class="player2.state == 'waiting' ? 'text-warning' : 'text-green'">@{{player2.state.toUpperCase()}}</span>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="card player-choice" :class="player2.state == 'waiting' ? 'waiting' : ''">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div v-if="player2.state == 'waiting'">
                                    <h3>?</h3>
                                </div>
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
                            <span :class="player1.state == 'waiting' ? 'text-warning' : 'text-green'">@{{player1.state.toUpperCase()}}</span>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="card player-choice" :class="player1.state == 'waiting' ? 'waiting' : ''">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div v-if="player1.choice != null">
                                    <h3>@{{player1.choice.toUpperCase()}}</h3>
                                </div>
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
    <div v-else class="row w-100">
        <div class="col-md-2 d-flex flex-column justify-content-center align-items-center scoreboard">
            <div class="row score align-items-center justify-content-center">
                @{{game.player2.score}}
            </div>
            <div class="row turn text-center">
                <h1>TURN @{{game.turn}}</h1>
            </div>
            <div class="row score align-items-center justify-content-center">
                @{{game.player1.score}}
            </div>
        </div>
        <div class="col-md-10 d-flex align-items-center justify-content-center">
            <div v-if="game.player1.state == 'waiting'">
                <div class="row mb-3">
                    <h3>Waiting for the other player...</h3>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center" v-else>
                <div class="row mb-3">
                    <h3>Choose an option..</h3>
                </div>
                <div class="row mb-5">
                    <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'rock' ? 'btn-primary' : 'btn-secondary'" @click="player1Choice('rock')">Rock</button></div>
                    <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'paper' ? 'btn-primary' : 'btn-secondary'" @click="player1Choice('paper')">Paper</button></div>
                    <div class="col"><button class="btn btn-game" :class="game.player1.choice == 'scissor' ? 'btn-primary' : 'btn-secondary'" @click="player1Choice('scissor')">Scissor</button></div>
                </div>
                <div class="row">
                    <div class="col"><button class="btn btn-tertiary" :disabled="game.player1.choice == null" @click="endTurn()">End Turn</button></div>
                </div>
            </div>
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
                        var player1 = parse.game.player1;
                        var player2 = parse.game.player2;

                        if(player1.info.email == app.playerData.email){
                            sidebar.player1 = player1;
                            sidebar.player2 = player2;

                            parse.game.player1 = player1;
                            parse.game.player2 = player2;
                        }else{
                            sidebar.player1 = player2;
                            sidebar.player2 = player1;

                            parse.game.player1 = player2;
                            parse.game.player2 = player1;
                        }

                        sidebar.show = true;
                        app.searchingMatch = false;
                        app.game = parse.game;

                        console.log(app.game, 'start');

                        break;
                    case 'game-update':
                        var player1 = parse.game.player1;
                        var player2 = parse.game.player2;

                        if(player1.info.email == app.playerData.email){
                            sidebar.player1 = player1;
                            sidebar.player2 = player2;

                            parse.game.player1 = player1;
                            parse.game.player2 = player2;
                        }else{
                            sidebar.player1 = player2;
                            sidebar.player2 = player1;

                            parse.game.player1 = player2;
                            parse.game.player2 = player1;
                        }

                        app.game = parse.game;

                        console.log(app.game, 'update');

                        break;
                    default:
                        app.room = parse.room;

                        if(app.room.players.length == 2){
                            var data = {
                                command: 'start-game'
                            }
                            app.connection.send(JSON.stringify(data));
                        }
                }
                             
            };
            this.connection.onclose = function(event) {
                
            }
        },
        mounted: function(){

        },
        methods: {
            endTurn: function(){
                if(this.game.player1.choice == null){
                    return;
                }

                var data = {
                    game: this.game,
                    command: 'end-turn'
                }

                app.connection.send(JSON.stringify(data));
            },
            player1Choice: function(choice){
                this.game.player1.choice = choice;
            }
        },
    });
</script>
@endsection