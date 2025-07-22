<?php
namespace App\Http\Utilities;

use Carbon\Carbon;
use App\Models\Tarahi;


class TarahiCreate
{
    public static function all($request)
    {
        $time = Carbon::now();
        $request->validate([
            'entekhab' => 'required',
            'text'=> 'required|string|max:65535',
            'group'=> 'required|max:255',
            'type'=> 'required|max:255',
            'title'=> 'required|string|max:255',
            'category'=> 'required|max:255',
            'price' => 'numeric',
        ]);

        if ($request->id)
        {

            $tarahis = Tarahi::find($request->id)->update([
                'slug'=>$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] . '_' . $request->title,
                'price'=>$request->price,
                'text'=> $request->text,
                'title'=> $request->title,
                'group'=>$request->group['id'],
                'type'=> $request->type['id'],
                'category'=> $request->category['id'],
                'status'=> 0,
            ]);
        }
        else
        {


            $tarahis = Tarahi::create([
                'slug'=>$request->group['name'].'_'. $request->type['name'] .'_'.$request->category['name'] .'_'. $request->title,
                'title'=> $request->title,
                'text'=> $request->text,
                'price'=>$request->price,
                'user_id' => auth()->user()->id,
                'group'=>$request->group['id'],
                'type'=> $request->type['id'],
                'category'=> $request->category['id'],
                'expired_at' => $time->addDay(15),
                'company_id' => $request->entekhab >0 ? $request->entekhab : null,
            ]);

        }

        return $tarahis;

    }

    public static function updateAdmin($request)
    {
        $request->validate([
            'id'=>'required',
            'text'=> 'required|string|max:65535',
            'group'=> 'required|max:255',
            'type'=> 'required|max:255',
            'title'=> 'required|string|max:255',
            'category'=> 'required|max:255',
            'status'=>'required',
        ]);

        $tarahis = Tarahi::find($request->id)->update([
            'slug'=>$request->group['name'] .'_'. $request->type['name'] .'_'. $request->category['name'] . '_' . $request->title,
            'price'=>$request->price,
            'user_id' => $request->user_id,
            'text'=> $request->text,
            'title'=> $request->title,
            'group'=>$request->group['id'],
            'type'=> $request->type['id'],
            'category'=> $request->category['id'],
            'status'=> $request->status,
        ]);

        return $tarahis;
    }
}
