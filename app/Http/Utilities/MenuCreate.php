<?php
namespace App\Http\Utilities;

use App\Models\Menu;

class MenuCreate
{
    public static function all($request)
    {
        $request->validate([
            'parent_id' => 'numeric||nullable',
            'name' => 'required',
        ]);

        if ($request->id) {

            $menus = Menu::find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'status' => $request->status,
            ]);
        }
        else
        {

            $menus = Menu::create([
                'user_id'=> auth()->user()->id,
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'status'=> 4,
            ]);
        }


        return $menus;

    }

}
