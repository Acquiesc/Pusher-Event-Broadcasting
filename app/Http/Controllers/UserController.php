<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function updateProfilePicture(Request $request) {
        $user = User::find(intval($request->input('user_id')));

        $img = $request->file('profile_picture');

        $imgName = time() . rand(1000, 9999) . '.' . $img->extension();
        
        $path = '/imgs/profile_pictures/';

        $img->move(public_path($path), $imgName);

        $user->profile_picture = $path . $imgName;
        $user->save();

        return back();
    }

    public function updateUsername(Request $request) {
        $user = User::find(intval($request->input('user_id')));

        $user->name = $request->input('username');

        $user->save();

        return response()->json(['username' => $request->input('username')], 200);
    }
}
