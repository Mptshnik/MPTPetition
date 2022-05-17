<?php

namespace App\Http\Controllers;



use App\Models\Petition;
use App\Models\Signature;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class PetitionController extends Controller
{
    public function index()
    {
        $allPetitions = Petition::all();
        $petitions = array();
        foreach ($allPetitions as $petition)
        {
            $signatures = $petition->signatures();
            $petitions[] = ['petition' => $petition, 'signatures' => $signatures->count()];
        }

        return ['response' => $petitions];
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
            $petition->image = $this->loadImageFile($request);
        }

        $petition->save();

        return $petition;
    }

    public function show($id)
    {
        $petition = Petition::find($id);

        if($petition == NULL)
        {
            return response()->json(['message' => 'Петиция не найдена']);
        }
        $signatures = $petition->signatures();

        return ['response' => $petition, 'signatures'=>$signatures->count()];
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
            $petition->image = $this->loadImageFile($request);
        }

        $petition->save();

        return $petition;
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

    private function loadImageFile(Request $request)
    {
        $image = $request->file('image');
        $fileName = time() . '.' . $image->getClientOriginalExtension();

        $img = Image::make($image->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->stream(); // <-- Key point

        Storage::disk('local')->put($fileName, $img, 'public');

        return Storage::disk('local')->url($fileName);
    }
}
