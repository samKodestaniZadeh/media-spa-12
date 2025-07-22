<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class section extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'section' ,
        'route' ,
        'parent_id' ,
        'name' ,
        'product',
        'tarahi',
        'sub',
        'status',
    ];

    public function sectionable()
    {
        return $this->morphTo();
    }

    public function menus()
    {
        return $this->morphedByMany(Menu::class, 'sectionable');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
