<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    public function login(Request $request)
    {

        $rules=array(
            'email'=>'required',
            'password'=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Не верные данные'
            ], 401);
        }

        $user = User::where('email', '=', $request->email)->first();
        if (!$user->hasVerifiedEmail()) {
            return ['message' => 'Не верифицирован'];
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('JWT', $token, 24 * 60 * 30); // 30 days


        return response([
            'message' => 'успешно',
            'token' => $token
        ])->withCookie($cookie);
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        $user->petitions;
        return ['user' => $user];
    }

    public function logout(Request $request)
    {

        $cookie = Cookie::forget('JWT');

        return response([
            'message' => 'успешно'
        ])->withCookie($cookie);
    }

    public function test()
    {
        //$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
       // $cookie = cookie('test', 'test', 24 * 60 * 30, '/', $domain, true); // 30 days
        $cookie = cookie('test', 'test', 24 * 60 * 30); // 30 days
        return response([
            'message' => 'test'
        ])->withCookie($cookie);
    }
}
