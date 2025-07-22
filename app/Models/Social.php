<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'tag',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function link()
    {
        return $this->belongsTo(Link::class,'id','linkable_id')->where('linkable_type',Social::class)->with('linkable');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class,'title','id');
    }
}
