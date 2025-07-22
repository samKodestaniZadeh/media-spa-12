<?php

namespace App\Models;


use App\Models\File;
use App\Models\Menu;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Discount;
use App\Models\ReqDesigner;
use Laravel\Scout\Searchable;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[SearchUsingPrefix(['title', 'slug', 'group','type','category', 'price', 'updated'])]
class Tarahi extends Model implements Viewable

{
    use HasFactory, Searchable, InteractsWithViews,Rateable;
    protected $fillable = [
        'user_id',
        'reqdesigner_id',
        'group',
        'type',
        'category',
        'date',
        'title',
        'text',
        'price',
        'discount',
        'comison',
        'tax',
        'complications',
        'total',
        'price_block',
        'status',
        'slug',
        'notification_id',
        'expired_at',
        'canceller_id',
        'company_id',
    ];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'group' => $this->attributes['group'] ?? null,
            'type' => $this->attributes['type'] ?? null,
            'category' => $this->attributes['category'] ?? null,
            'price' => $this->price,
            'updated_at' => $this->updated
        ];
    }

    public function discount()
    {
        return $this->morphOne(Discount::class, 'discountable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->with('image');
    }

    public function reqDesigner()
    {
        return $this->hasMany(ReqDesigner::class, 'tarahi_id', 'id')->where('status', 4);
    }

    public function registerDesigner()
    {
        return $this->belongsTo(ReqDesigner::class, 'reqdesigner_id', 'id')
            ->with('user')->with('file');
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
    public function favorite()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function offers()
    {
        return $this->hasMany(ReqDesigner::class, 'tarahi_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('status' , 4 )->whereNull('parent_id')->with('replies')->with('user');
    }

    public function userCanceller()
    {
        return $this->belongsTo(User::class, 'canceller_id', 'id');
    }

    public function menus()
    {
        return $this->morphToMany(Menu::class, 'menuable')->with('routes')->with('sections');
    }

    public function group()
    {
        return $this->belongsTo(Menu::class, 'group', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Menu::class, 'type', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Menu::class, 'category','id');
    }
    public function orders()
    {
        return $this->morphMany(Orderable::class, 'orderable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function installments()
    {
        return $this->morphToMany(Installment::class, 'installmentable');
    }
}
