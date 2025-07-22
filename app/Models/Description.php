<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Description extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'route' ,
        'subject' ,
        'text' ,
        'status',
    ];

    public function descriptionable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
