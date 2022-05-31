<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageLoader
{
    public static function loadImageFile(Request $request)
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
