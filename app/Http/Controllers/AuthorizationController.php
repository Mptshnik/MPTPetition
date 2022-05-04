<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthorizationController extends Controller
{
    public function unauthorized(Request $request)
    {
        return response(['message' => 'unauthorized']);
    }

    public function login(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user->hasVerifiedEmail()) {
            return ['message' => 'Не верифицирован'];
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
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
        $user = Auth::user();
        $user->petitions;
        return ['user' => $user];
    }

    public function logout(Request $request)
    {

        $cookie = Cookie::forget('JWT');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function test()
    {
        return response(['message' => 'test']);
    }
}
