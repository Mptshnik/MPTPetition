<?php

namespace App\Http\Controllers;



use App\Models\Petition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class PetitionController extends Controller
{
    public function create(Request $request)
    {
        $petition = new Petition();
        $petition->name = $request->name;
        $petition->description = $request->description;
        $petition->user_id = Auth::user()->getAuthIdentifier();


        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point

            Storage::disk('local')->put($fileName, $img, 'public');

            $petition->image = Storage::disk('local')->url($fileName);
        }



        $petition->save();

    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}
