<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{

    public function register(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if($user == null)
        {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->surname = $request->surname;
            $user->save();
            $user->sendEmailVerificationNotification();

            return [
                'message' => 'Пользователь успешно зарегистрирован',
                'verification' => "На почту $user->email отправлено сообщение с подтвеждением"
            ];
        }

        return [
            'message' => 'Пользователь уже существует!'
        ];
    }
}
