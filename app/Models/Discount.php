<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'expired',
        'percent',
        'discountable_type',
        'discountable_id'
    ];
    public function discountable()
    {
        return $this->morphTo()->where('status',4)->OrWhere('status',6)->with('image')->withCount('comments')->with('menus')->with('user')
        ->withCount('orders')->withAvg('ratings', 'rating');
    }

}
