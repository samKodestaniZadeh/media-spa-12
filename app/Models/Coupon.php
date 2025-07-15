<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'price',
        'expired_at',
        'order_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function couponable()
    {
        return $this->morphTo();
    }
}
