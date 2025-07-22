<?php
namespace App\Http\Utilities;


use App\Models\Sectionable;

class SectionableCreate
{
    public static function all($request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'sectionable_type' => 'required',
            'sectionable_id' => 'required|numeric',
        ]);

        $sections = Sectionable::updateorcreate([
            'section_id'=> $request->id,
            'sectionable_type' => $request->sectionable_type ,
            'sectionable_id' => $request->sectionable_id,
        ]);

        return $sections;

    }

    public static function del($request)
    {
        $sections = Sectionable::where('section_id',$request->id )->where('sectionable_type',$request->type)->where('sectionable_id',$request->del)->delete();

        return $sections;
    }
}
