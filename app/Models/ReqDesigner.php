<?php

namespace App\Models;

use App\Models\Rate;
use App\Models\User;
use App\Models\Image;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReqDesigner extends Model
{
    use HasFactory,Rateable;
    protected $fillable = [
        'user_id',
        'tarahi_id',
        'expired',
        'status',
        'block_price',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->with('image')->with('rates')->withCount('rates');
    }

    public function reqDesigner()
    {
        return $this->belongsTo(Tarahi::class,'tarahi_id','id')->where('status','>',1);
    }
    public function tarahiRegister()
    {
        return $this->belongsTo(Tarahi::class,'id','reqdesigner_id')->withCount('views')->with('user');
    }

    public function file()
    {
        return $this->morphOne(File::class,'fileable');
    }
    public function userCanceller()
    {
        return $this->belongsTo(User::class,'canceller_id','id');
    }
    public function tarahiRegisterStatus()
    {
        return $this->belongsTo(Tarahi::class,'id','reqdesigner_id')->where('status',5)->OrWhere('status',7)->OrWhere('status',8);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function installments()
    {
        return $this->morphToMany(Installment::class, 'installmentable');
    }
    public function discount()
    {
        return $this->morphOne(Discount::class, 'discountable');
    }
}
