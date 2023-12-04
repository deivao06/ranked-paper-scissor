@extends('master')

@section('sidebar')
<div class="side-bar">
    <div class="d-flex flex-column h-100">
        <div class="row">
            <div class="col">
                <a href="{{route('queue', ['type' => 'normal'])}}" class="btn btn-tertiary btn-normal-game w-100 mb-2">Normal Game</a>
                <button type="button" class="btn btn-primary btn-ranked-game w-100 mb-2">Ranked Game</button>
                <button type="button" class="btn btn-secondary btn-custom-game w-100 mb-2">Custom Game</button>
            </div>
        </div>
        <div class="row flex-grow-1 align-items-end">
            <div class="col">
                <a type="button" class="btn btn-primary btn-ranked-game w-30" href="{{route('logout')}}">Logout</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main-content')
<div class="d-flex align-items-center justify-content-center h-100">
    <h1>Leaderboard</h1>
</div>
@endsection

@section('script')
<script>
    
</script>
@endsection