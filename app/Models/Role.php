<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withCount('ordersProduct')->withCount('ordersTarahi')->withCount('ratings')
        ->with('image')->with('socials')->with('ratings');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
