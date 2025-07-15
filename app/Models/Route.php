<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'status'
    ];
    public function routeable()
    {
        return $this->morphTo();
    }
    public function menus()
    {
        return $this->morphedByMany(Menu::class, 'routeable')->where('parent_id',null)->with('children')->with('routes')
        ->with('descriptions')->with('sections')->with('products')->with('tarahis')->with('namad');
    }
    public function descriptions()
    {
        return $this->morphToMany(Description::class, 'descriptionable');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function sub()
    {
        return $this->belongsTo(Routeable::class,'id','route_id');
    }
    public function subs()
    {
        return $this->hasMany(Routeable::class,'route_id','id');
    }
    public function menu()
    {
        return $this->morphedByMany(Menu::class, 'routeable')->with('sections');
    }
}
