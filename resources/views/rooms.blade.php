@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row border-bottom py-3">
        <div class="col-12">
            <div class="d-flex justify-content-evenly">
                <a href="/rooms"><h1>Laravel Chats</h1></a>
                <form action="{{ route('logout')}}" id="logout_form" class="visually-hidden" method="POST">
                    @csrf
                    <button type="submit">logout</button>
                </form>
                <button onclick="setOnline('{{auth()->user()->id}}')" class="btn btn-danger">Logout</button>
            </div>
        </div>

        <script scr='/js/manageOnline.js'></script>

    </div>
    <div class="row my-3">
        <div class="col text-center">
            <a href="/room/create" class="btn btn-primary">Create a Room</a>
        </div>
    </div>
    @if(count($rooms) > 0)
    @foreach($rooms as $room)
        <div class="row my-3">
            @if($room->user_id == auth()->user()->id)
            <div class="d-flex justify-content-end">
                <form action="/room/{id}/delete">
                    @csrf
                    <button type="submit" class="btn"><i class="bi bi-trash"></i></button>
                </form>
            </div>
            @endif
            <a href="/chat/{{$room->id}}" class="col p-3 rounded" style="background-color: rgba(211, 211, 211, .3);">
                <p>Room: {{$room->name}}</p>
                <p>Active Users: {{$room->active_users}}</p>
                <p>Public: {{$room->public}}</p>
            </a>
        </div>
    @endforeach
    @else
    <div class="row">
        <div class="col text-center">
            <p>It looks like there aren't currently any active rooms!  <a href="/room/create">Create a new one</a> to start chatting.</p>
        </div>
    </div>
    @endif
</div>

@endsection