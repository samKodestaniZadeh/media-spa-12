<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Models\Coupon;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Discount;
use App\Models\Installment;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[SearchUsingPrefix(['name','name_en','group','type','category','price','updated'])]
class Product extends Model implements Viewable
{
    use HasFactory,Searchable,InteractsWithViews,Rateable;
    protected $casts = [
        'browser' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'name_en',
        'group',
        'type',
        'category',
        'text',
        'sings',
        'design_type',
        'prerequisites',
        'prerequisite_version',
        'additional_facilities',
        'browser_compatibility',
        'demo_link',
        'original_link',
        'price',
        'version',
        'file',
        'status',
        'comison',
        'tax',
        'complications',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'name_en' => $this->name_en,
            'group' => $this->attributes['group'] ?? null,
            'type' => $this->attributes['type'] ?? null,
            'category' => $this->attributes['category'] ?? null,
            'price' => $this->sort,
            'updated_at'=> $this->updated
        ];
    }
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
    public function file()
    {
        return $this->morphOne(File::class,'fileable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('status' , 4 )->whereNull('parent_id')->with('replies')->with('user');
    }
    public function favorite()
    {
        return $this->morphMany(Favorite::class,'favoritable');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('image')->with('profile')->with('roles')->with('socials');
    }

    public function discount()
    {
        return $this->morphOne(Discount::class,'discountable')->where('expired','>',new Carbon);
    }
    public function coupon()
    {
        return $this->morphOne(Coupon::class,'couponable');
    }
    public function orders()
    {
        return $this->morphToMany(Order::class, 'orderable');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
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
    public function changes()
    {
        return $this->hasMany(ChangeProduct::class,'product_id','id');
    }

    public function registerDesigner()
    {
        return $this->belongsTo(ReqDesigner::class, 'reqdesigner_id', 'id')
            ->with('user')->with('file');
    }
    public function installments()
    {
        return $this->morphToMany(Installment::class, 'installmentable');
    }
}
