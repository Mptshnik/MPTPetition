<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{

    public function register(Request $request)
    {
        $rules=array(
            'email'=>'required|email|ends_with:@mpt.ru',
            'name'=>'required',
            'surname'=>'required',
            'password'=>['required', 'min:8', Password::min(8)->mixedCase()->numbers()]
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

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
