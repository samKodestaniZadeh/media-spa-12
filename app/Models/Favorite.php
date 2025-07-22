<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_type',
        'favoritable_id'
    ];

    public function favoritable ()
    {
        return $this->morphTo()->with('image')->withAvg('ratings', 'rating')->with('discount');
    }

}
