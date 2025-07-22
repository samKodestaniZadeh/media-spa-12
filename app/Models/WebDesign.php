<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Models\Discount;
use App\Models\Installment;
use App\Models\ReqDesigner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebDesign extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'name_en',
        'group',
        'type',
        'category',
        'text',
        'price',
        'status',

    ];

    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('image')->with('roles')->with('socials');
    }
    public function discount()
    {
        return $this->morphOne(Discount::class,'discountable')->where('expired','>',new Carbon);
    }
    public function menus()
    {
        return $this->morphToMany(Menu::class,'menuable')->with('routes')->with('sections');
    }
    public function group()
    {
        return $this->belongsTo(Menu::class, 'group','id');
    }
    public function type()
    {
        return $this->belongsTo(Menu::class, 'type','id');
    }
    public function category()
    {
        return $this->belongsTo(Menu::class, 'category','id');
    }
    public function sections()
    {
        return $this->morphToMany(section::class, 'sectionable');
    }
    public function installments()
    {
        return $this->morphToMany(Installment::class, 'installmentable');
    }
    public function registerDesigner()
    {
        return $this->belongsTo(ReqDesigner::class, 'reqdesigner_id', 'id')
            ->with('user')->with('file');
    }
}
