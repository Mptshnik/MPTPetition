<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ImageLoader
{
    public static function loadImageFile(Request $request)
    {
       /* $image = $request->file('image');
        $fileName = time() . '.' . $image->getClientOriginalExtension();

        $img = Image::make($image->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->stream(); // <-- Key point

        Storage::disk('local')->put($fileName, $img, 'public');
        $pathToFile = storage_path().'/app/images/'.$fileName;*/

        $path = $request->file('image')->store('images');


        $filename = storage_path().'/app/'.$path;

        $filename = str_replace('/var/www/u1659515/data/www/','https://', $filename);

        return base64_encode($filename);
    }
}
