<?php

namespace App\Http\Controllers;

use App\Models\ImageLoader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);
        if($user == null)
        {
            return ['message' => 'Пользователь не найден'];
        }

        return ['user' => $user, 'petitions' => $user->petitions()->with('votedUsers')->withCount('signatures')->get()];
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return response()->json(['message' => 'успешно']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules=array(
            'email'=>'required|email|ends_with:@mpt.ru|max:255',
            'name'=>'required|max:255',
            'surname'=>'required|max:255',
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $user->update($request->only(['name', 'email', 'surname']));

        if($request->hasFile('image'))
        {
            $user->image = ImageLoader::loadImageFile($request);
        }
        else
        {
            $user->image = null;
        }
        $user->save();

        return ['user' => $user, 'petitions' => $user->petitions()->with('votedUsers')->withCount('signatures')->get()];
    }

}
