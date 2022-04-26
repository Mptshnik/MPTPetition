<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {

        if($jwt = $request->cookie('JWT'))
        {
            $request->headers->set('Authorization', "Bearer $jwt");
        }

        if($request->User()->hasVerifiedEmail()){
            return[
                'message' => 'Already verified'
            ];
        }

        $request->User()->sendEmailVerificationNotification();

        return [
            'status' => 'verification-link-sent'
        ];
    }

    public function verify(EmailVerificationRequest $request)
    {

        if($request->User()->hasVerifiedEmail()){
            return[
                'message' => 'Already verified'
            ];
        }

        if($request->User()->markEmailAsVerified()){


            event(new Verified($request->User()));
        }

        return[
            'message' => 'Email has been verified'
        ];
    }
}
