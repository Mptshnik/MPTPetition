<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use Illuminate\Http\Request;
use App\Models\Signature;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SignaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Signature::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        if(Signature::where([
            ['user_id', Auth::user()->getAuthIdentifier()],
            ['petition_id', $id],
            ])->exists())
        {
            return response()->json(['message'=>'Петиция уже подписана']);
        }
        $signature = new Signature();
        $signature->user_id = Auth::user()->getAuthIdentifier();
        $signature->petition_id = $id;
        $signature->save();


        return response()->json(['message' => 'успешно']);
    }

    public function checkIfSigned($id)
    {
        if(Signature::where([
            ['user_id', Auth::user()->getAuthIdentifier()],
            ['petition_id', $id],
            ])->exists())
        {
            return response()->json([
                'message'=>'Петиция подписана',
                'signed' => 'true'
            ]);
        }
        else return response()->json([
        'message'=>'Петиция не подписана',
        'signed' => 'false'
    ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $signature = Signature::where([
            ['user_id', Auth::user()->getAuthIdentifier()],
            ['petition_id', $id],
            ]);

        if($signature->exists())
        {
            $signature->delete();

            return response()->json([
                'message' => 'успешно'
            ]);
        }
        else return response(['message'=>'Петиция не подписана']);
    }

}
