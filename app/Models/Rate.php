<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $table = 'ratings';
    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'status',
        'rateable_type',
        'rateable_id',
    ];
    public function rateable()
    {
        return $this->morphTo()->pluck('rating');
    }

}
