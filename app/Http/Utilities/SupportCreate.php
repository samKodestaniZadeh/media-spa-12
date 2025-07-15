<?php
namespace App\Http\Utilities;

use App\Models\Support;

class SupportCreate
{
    public static function all($request)
    {
        // dd($request->parent_id);
        if($request->parent_id)
        {
            Support::find($request->parent_id)->update([
                'status'=>0,
                'updated_at'=> time(),
            ]);

            $supports = Support::create([
                'user_id' => auth()->user()->id,
                'parent_id' => $request->parent_id,
                'recepiant' => $request->recepiant,
                'subject' => $request->subject,
                'text' => $request->text,
                'status' => 0,
            ]);
        }
        else
        {

            $supports = Support::findOrfail($request->parent_id);
            $supports->update([
                'status'=>4,
            ]);

            $supports = Support::create([
                'user_id' => auth()->user()->id,
                'parent_id' => $request->parent_id,
                'destination'=> $request->destination ,
                'recepiant' => $request->recepiant,
                'subject' => $request->subject,
                'text' => $request->text,
                'status' => 4,
                ]);
        }
        return $supports;

    }

    public static function Admin($request)
    {

        if ($request->parent_id) {


        }
        else
        {

            if($request->destination)
            {
                $supports = Support::create([
                    'user_id' => auth()->user()->id,
                    'destination'=> $request->destination['id'],
                    'recepiant' => $request->recepiant['id'],
                    'subject' => $request->subject['id'],
                    'text' => $request->text,
                ]);


            }
            else
            {

                $supports = Support::create([
                    'user_id' => auth()->user()->id,
                    'recepiant' => $request->recepiant['id'],
                    'subject' => $request->subject['id'],
                    'text' => $request->text,

                ]);

            }
        }


        return $supports;

    }

}
