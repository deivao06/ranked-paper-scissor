@extends('master')

@section('main-content')
<div id="app-normal">
    <div v-if="searchingMatch" class="d-flex flex-column justify-content-center align-items-center search-loader">
        <h3>Searching Match...</h3>
        <div class="lds-ripple mb-3"><div></div><div></div></div>
        <a href="{{route('master')}}" class="btn btn-primary">Cancel</a>
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
            searchingMatch: false,
        },
        created: function(){
            this.connection = new WebSocket('ws://localhost:5050');

            this.connection.onopen = function(event) {      
                app.searchingMatch = true;
                app.connection.send(JSON.stringify(app.playerData));
            };

            this.connection.onmessage = function(event) {
                console.log('feijao');          
                console.log(event.data);
            };

            this.connection.onclose = function(event) {
                console.log('batata');          
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