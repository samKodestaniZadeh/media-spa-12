<?php

namespace App\Models;

use App\Models\Route;
use App\Models\Product;
use App\Models\Support;
use App\Models\Description;
use App\Models\section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Menu extends Model
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

    public function menuable()
    {
        return $this->morphTo();
    }
    // public function parent() {
    //     return $this->hasMany(Menu::class,'parent_id')->with('children');
    // }

    public function children()
    {
        return $this->hasMany(Menu::class,'parent_id','id')->with('children')->with('routes')->with('descriptions')->with('sections')
        ->with('social')->with('namad')->withCount('products')->withCount('tarahis');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'menuable');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function supports()
    {
        return $this->morphedByMany(Support::class, 'menuable');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'menuable')->with('image');
    }
    public function tarahis()
    {
        return $this->morphedByMany(Tarahi::class, 'menuable');
    }
    public function routes()
    {
        return $this->morphToMany(Route::class, 'routeable');
    }

    public function descriptions()
    {
        return $this->morphToMany(Description::class, 'descriptionable');
    }
    public function sections()
    {
        return $this->morphToMany(section::class, 'sectionable')->with('menus');
    }

    public function social()
    {
        return $this->belongsTo(Social::class,'id','title');
    }
    public function socials()
    {
        return $this->morphedByMany(Social::class, 'menuable');
    }
    public function webDesigns()
    {
        return $this->morphedByMany(WebDesign::class, 'menuable')->with('image');
    }
    public function namad()
    {
        return $this->belongsTo(Namad::class,'id','title');
    }
}
