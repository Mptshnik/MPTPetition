<?php

namespace App\Http\Controllers;

use App\Models\User;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthorizationController extends Controller
{
    public function register(Request $request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password')))
        {
            return response([
                'message' => 'Invalid data'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('JWT', $token, 24 * 60 * 30); // 30 days

        return response([
            'message' => 'success',
            'token' => $token
        ])->withCookie($cookie);
    }

    public function getCurrentUser()
    {
        if(!Auth::check())
        {
            return response([
                'message' => 'unauthorized'
            ]);
        }

        return Auth::user();
    }

    public function logout(Request $request)
    {
        if(!Auth::check())
        {
            return response([
                'message' => 'unauthorized'
            ]);
        }

        $cookie = Cookie::forget('JWT');

        return response([
           'message' => 'success'
        ])->withCookie($cookie);
    }
}
