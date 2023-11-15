<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Events\ChatBroadcast;
use App\Events\OnlineBroadcast;

use App\Models\ChatRoom;

class PusherController extends Controller
{
    //send messages in chatroom
    public function broadcast(Request $request) {
        broadcast(new ChatBroadcast($request->get('profile_picture'), 
                                        $request->get('username'),
                                        $request->get('user_id'), 
                                        $request->get('message'),
                                        $request->get('room_name')))->toOthers();

        return view('broadcast', ['profile_picture' => $request->get('profile_picture'),
                                    'username' => $request->get('username'),
                                    'user_id' => $request->get('user_id'), 
                                    'message' => $request->get('message')]);
    }

    //receive messages in chat room
    public function receive(Request $request) {
        return view('receive', ['profile_picture' => $request->get('profile_picture'),
                                'username' => $request->get('username'), 
                                'user_id' => $request->get('user_id'), 
                                'message' => $request->get('message')]);
    }

    //send online true/false to other users
    public function online(Request $request) {
        broadcast(new OnlineBroadcast($request->get('user_id'), 
                                        $request->get('online')))->toOthers();

        return response()->json(['user_id' => ''.$request->get('user_id'),
                                    'online' => $request->get('online')], 200);
    }
}
