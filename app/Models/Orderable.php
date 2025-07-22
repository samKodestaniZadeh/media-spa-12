<?php

namespace App\Models;

use App\Models\Link;
use App\Models\Order;
use App\Models\Tarahi;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orderable extends Model
{
    use HasFactory;

    protected $table = 'orderables';
    // public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'price',
        'count',
        'discount',
        'coupon',
        'total',
        'comison',
        'tax',
        'col',
        'orderable_type',
        'orderable_id',
    ];

    public function orderable()
    {
        return $this->morphTo()->with('menus')->with('user')->with('registerDesigner')->with('image');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->with('profile')->with('image');
    }
    public function links()
    {
        return $this->morphToMany(Link::class,'linkable');
    }
    public function link()
    {
        return $this->morphOne(Link::class,'linkable');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id')->with('user');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'orderable_id','id')->with('menus')->with('user')->with('discount')->with('image')
        ->withCount('orders')->withCount('comments')->withAvg('ratings', 'rating');
    }
    public function tarahi()
    {
        return $this->belongsTo(Tarahi::class,'orderable_id','id')->with('menus')->with('user')->with('discount')->withCount('orders')
        ->withCount('comments');
    }
}

