<?php
namespace App\Http\Utilities;

use App\Models\Route;

class RouteCreate
{
    public static function all($request)
    {
        $request->validate([
            'name' => 'required',
        ]);


        if ($request->id) {

            $routes = Route::find($request->id)->update([
                'user_id'=> auth()->user()->id,
                'name' => $request->name,
                'status'=> 4,
            ]);
        }
        else
        {

            $routes = Route::create([
                'user_id'=> auth()->user()->id,
                'name' => $request->name,
                'status'=> 4,
            ]);
        }

        return $routes;

    }

}
