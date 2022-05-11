<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function show($id)
    {
        $user = User::find($id);
        if($user == null)
        {
            return ['message' => 'Not found'];
        }
        $user->petitions;
        return ['user' => $user];
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));
        $user->save();

        return $user;
    }
}
