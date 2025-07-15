<?php
namespace App\Http\Utilities;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageCreate
{

    public static function all($request,$models,$class)
    {

        $image = $request->file('image')?$request->file('image')->store('images'):null;

        if ($request->id)
        {
            if($image && $models->image && $image !== $models->image->url)
            {
                Storage::delete($models->image->url);
                $images = $models->image()->update([
                    'url'=>  $image,
                    'imageable_type'=>$class,
                    'imageable_id'=> $models->id,
                    'status'=> $request->status,
                ]);
            }
            else
            {
                $images = $models->image()->update([
                    'status'=> $request->status,
                ]);
            }
        }
        else
        {
            $images = Image::create([
                'user_id'=>auth()->user()->id,
                'url'=>$image,
                'imageable_type'=>$class,
                'imageable_id'=>$models->id
            ]);
        }


        return $images;
    }
}
