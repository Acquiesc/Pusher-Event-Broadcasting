<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\OnlineBroadcast;

use App\Models\ChatRoom;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //send user online broadcast to other active chat rooms
        broadcast(new OnlineBroadcast(auth()->user()->id, true))->toOthers();

        $rooms = ChatRoom::latest()->get();

        return view('rooms')->with('rooms', $rooms);
    }

    public function chat($id)
    {
        $room = ChatRoom::find($id);

        $room->active_users = $room->active_users + 1;

        $room->save();

        return view('chat')->with('room', $room);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $room = new ChatRoom;

        $room->user_id = auth()->user()->id;
        $room->name = 'Room' . rand(9999, 999999999);
        $room->active_users = 0;
        $room->public = true;

        $room->save();

        return redirect('/chat/' . $room->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = ChatRoom::find($id);

        $room->delete();

        return redirect('/rooms');
    }
}
