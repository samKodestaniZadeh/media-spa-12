<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'bank_id',
        'account_name',
        'account_number',
        'cart_number',
        'shaba_number',
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

    public function menu()
    {
        return $this->belongsTo(Menu::class,'bank_id','id');
    }
}
