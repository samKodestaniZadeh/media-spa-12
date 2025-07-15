<?php
namespace App\Http\Utilities;

use App\Models\Profile;

class ProfileCreate
{
    public static function all($request,$users)
    {
        $request->validate([
            'name'=> 'required',
            'name_en'=> 'required',
            'group' => 'required',
            'type' => 'required',
            'category' => 'required',
            'text'=> 'required|min:100',
            'demo_link'=> 'required|URL',
            'version'=> 'required',
            'file'=> 'required',
            'image'=> 'required',

        ]);

        if ($request->id) {


        }
        else
        {

            $profiles = Profile::Create([
                'user_id' => $users->id,
                'ostan' => $request->ostan,
                'shahr' => $request->shahr,
                'address' => $request->address,
                'birth'=> $request->birth,
                'gender'=> $request->gender,
                'biography'=> $request->biography,
                'wallet'=> 0,
                'status' => 0,
                'notification'=> 1,
            ]);

        }

        return $profiles;

    }
}
