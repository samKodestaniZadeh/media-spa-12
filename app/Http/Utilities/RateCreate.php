<?php
namespace App\Http\Utilities;

use App\Models\Rate;

class RateCreate
{
    public static function create($user_id,$rating,$comment,$rateable_type,$rateable_id)
    {

        $rates = Rate::create([
            'user_id'=> $user_id,
            'rating' => $rating,
            'comment'=> $comment,
            'rateable_type' => $rateable_type,
            'rateable_id' => $rateable_id,
        ]);


        return  $rates;

    }

}
