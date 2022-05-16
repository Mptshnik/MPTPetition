<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if($user == null)
        {
            return ['message' => 'Ошибка. Пользователь не найден.'];
        }

        if($request['password'] == $request['repeat-password'])
        {
            $user->password = Hash::make($request['password']);
            $user->save();
            return ['message' => 'Пароль успешно изменен'];
        }

        return ['message' => 'Ошибка. Пароли не совпадают'];
    }

    public function sendPasswordChangeEmail(Request $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT)
        {
            return ['message' => 'Письмо с изменением пароля отправлено на почту'];
        }

        return ['message' => 'Произошла ошибка'];
    }

    public function resetPassword(Request $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if($status === Password::PASSWORD_RESET)
        {
            $cookie = Cookie::forget('JWT');

            return response([
                'message' => 'Пароль изменен'
            ])->withCookie($cookie);
        }
        else
        {
            return ['message' => 'Не удалось изменить пароль'];
        }

    }
}
