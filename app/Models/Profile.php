<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'wallet',
        'ostan',
        'shahr',
        'address',
        'birth',
        'gender',
        'biography',
        'bank_name',
        'account_name',
        'account_number',
        'cart_number',
        'shaba_number',
        'notification',
        'mobile',
        'email',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
