<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'count' ,
        // 'installmentable_type',
        // 'installmentable_id',
    ];
    public function installmentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
