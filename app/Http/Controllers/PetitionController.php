<?php

namespace App\Http\Controllers;



use App\Models\ImageLoader;
use App\Models\Petition;
use App\Models\Signature;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class PetitionController extends Controller
{
    public function indexRecent()
    {
        $result = Petition::with('votedUsers')->withCount('signatures')->with('author')->paginate(10);
        $sortedResult = $result->getCollection()->sortByDesc('created_at')->values();
        $result->setCollection($sortedResult);

        return $result;
    }

    public function indexDesc()
    {
        $result = Petition::with('votedUsers')->withCount('signatures')->with('author')->paginate(10);
        $sortedResult = $result->getCollection()->sortByDesc('signatures_count')->values();
        $result->setCollection($sortedResult);

        return $result;
    }

    public function index()
    {
        return Petition::with('votedUsers')->withCount('signatures')->with('author')
            ->paginate(10);
    }

    public function search($name)
    {
       // $result = DB::table('petitions')->where('name', 'like', '%'.$request->name.'%');
       $result = Petition::with('votedUsers')->withCount('signatures')->with('author')
           ->where('name', 'like', '%'.$name.'%')->get();
       //with('votedUsers')->withCount('signatures')->with('author')->where('name', 'like', '%'.$request->name.'%')->paginate(10);
        if($result == NULL)
        {
            return response()->json(['message' => 'Петиция с таким названием не найдена']);
        }
        return $result;
    }

    public function store(Request $request)
    {
        $rules=array(
            'name'=>'required|unique:petitions,name',
            'description'=>'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $petition = new Petition();
        $petition->name = $request->name;
        $petition->description = $request->description;
        $petition->user_id = Auth::user()->getAuthIdentifier();

        if($request->hasFile('image'))
        {
            $petition->image = ImageLoader::loadImageFile($request);
        }

        $petition->save();

        $test = base64_decode($petition->image);

        return [$petition, $test];
    }

    public function show($id)
    {
        $petition = Petition::with('votedUsers')->withCount('signatures')->with('author')->find($id);

        return $petition;
    }

    public function update(Request $request, $id)
    {
        $petition = Petition::find($id);
        if($petition == NULL)
        {
            return response()->json(['message' => 'Петиция не найдена']);
        }

        if($petition->name != $request->name)
        {
            $rules=array(
                'name'=>'required|unique:petitions,name',
                'description'=>'required'
            );
        }
        else
        {
            $rules=array(
                'name'=>'required',
                'description'=>'required'
            );
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $petition->update($request->only(['name', 'description']));
        if($request->hasFile('image'))
        {
            $petition->image = ImageLoader::loadImageFile($request);
        }
        else
        {
            $petition->image = null;
        }

        $petition->save();
        return Petition::with('votedUsers')->withCount('signatures')->with('author')->find($id);
        //return $petition->with('votedUsers')->withCount('signatures')->with('author');
    }

    public function destroy($id)
    {
        $petition = Petition::find($id);
        if($petition == NULL)
        {
            return response()->json(['message' => 'Петиция не найдена']);
        }

        $petition->delete();
        return response()->json([
            'message' => 'успешно'
        ]);
    }
}
