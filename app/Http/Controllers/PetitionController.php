<?php

namespace App\Http\Controllers;



use App\Models\Petition;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class PetitionController extends Controller
{
    public function index()
    {
        return Petition::all();
    }

    public function store(Request $request)
    {
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
            return response()->json(['message' => 'Not found']);
        }

        return $petition;
    }

    public function update(Request $request, $id)
    {
        $petition = Petition::find($id);
        if($petition == NULL)
        {
            return response()->json(['message' => 'Not found']);
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
            return response()->json(['message' => 'Not found']);
        }

        $petition->delete();
        return response()->json([
            'message' => 'success'
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
