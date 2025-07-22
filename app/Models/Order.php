<?php

namespace App\Models;


use App\Models\Tarahi;
use App\Models\Product;
use App\Models\Profile;
use App\Models\orderuser;
use App\Models\ordertarahi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'price',
        'count',
        'discount',
        'coupon',
        'total',
        'tax',
        'col',
        'payment',
        'balance',
        'status',

    ];
    public function orderable()
    {
        return $this->morphTo()->whith('roles');
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class,Orderable::class ,'order_id','orderable_id','id')->with('image')->with('user');
    // }
    public function products()
    {
        return $this->morphedByMany(Product::class,'orderable')->with('menus')->with('user')->with('image');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->with('profile')->with('image');
    }
    public function subOrder()
    {
        return $this->hasMany(Orderable::class ,'order_id','id')->with('link')->with('orderable');
    }

}
