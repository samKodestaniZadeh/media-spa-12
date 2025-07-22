<?php
namespace App\Http\Utilities;

use App\Models\File;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class FileCreate
{
    public static function all($request,$models,$class)
    {

        $file = $request->file('file')?$request->file('file')->store('files'):null;

        if ($request->id) {

            if($file && $models->file && $file !== $models->file->url)
            {
                Storage::delete( $models->file);
                $files = $models->file()->update([
                    'url'=>  $file,
                    'fileable_type'=>$class,
                    'fileable_id'=> $models->id,
                    'status'=> $request->status,
                ]);
            }
            else
            {
                $files = $models->file()->update([
                    'status'=> $request->status,
                ]);
            }
        }
        else
        {

            $files = File::create([
                'user_id' => auth()->user()->id,
                'url'=>  $file,
                'fileable_type'=>$class,
                'fileable_id'=>$models->id
            ]);
        }
        return $files;

    }
    public static function create($request,$models,$class)
    {
        $file = $request->file('file')?$request->file('file')->store('files'):null;
        $files = File::create([
            'user_id' => auth()->user()->id,
            'url'=>  $file,
            'fileable_type'=>$class,
            'fileable_id'=>$models->id
        ]);
        return $files;
    }
}
