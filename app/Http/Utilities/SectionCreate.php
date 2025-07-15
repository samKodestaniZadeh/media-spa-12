<?php
namespace App\Http\Utilities;

use App\Models\Section;

class SectionCreate
{
    public static function all($request)
    {
        $request->validate([
            'name' => 'required',
        ]);


        if ($request->id) {

            $sections = Section::find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'name' => $request->name,
                'status' => 4,
            ]);

        }
        else
        {

            $sections = Section::create([
                'user_id'=> auth()->user()->id,
                'name' => $request->name,
                'status' => 4,
            ]);
        }

        return $sections;

    }

}
