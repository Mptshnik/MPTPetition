<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

    public function register(Request $request)
    {
        $user = new User();
       // $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_code = sha1(time());
        $user->save();


        //Если пользователь существует
        return [
            'message' => 'Пользователь уже существует!'
        ];
    }
}
