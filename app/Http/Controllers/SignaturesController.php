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
            return response()->json(['message'=>'already signed']);
        }
        $signature = new Signature();
        $signature->user_id = Auth::user()->getAuthIdentifier();
        $signature->petition_id = $id;
        $signature->save();
        return response()->json(['message' => 'Thank you']);
    }

    public function checkIfSigned($id)
    {
        if(Signature::where([
            ['user_id', Auth::user()->getAuthIdentifier()],
            ['petition_id', $id],
            ])->exists())
        {
            return response()->json(['message'=>'signed']);
        }
        else return response()->json(['message'=>'not signed']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
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
                'message' => 'success'
            ]);
        }
        else return response(['message'=>'not signed']);
    }
}
