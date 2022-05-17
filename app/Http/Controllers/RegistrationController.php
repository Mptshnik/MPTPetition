<?php

namespace App\Http\Controllers;

use App\Models\ImageLoader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

            if($request->hasFile('image'))
            {
                $user->image = ImageLoader::loadImageFile($request);
            }

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
