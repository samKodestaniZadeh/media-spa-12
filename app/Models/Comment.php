<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected  $fillable= [
        'user_id',
        'parent_id',
        'commentable_type',
        'commentable_id',
        'text',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->with('image');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies')->with('user');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class,'product_id','id');
    // }

    public function commentable()
    {
        return $this->morphTo();
    }
}
