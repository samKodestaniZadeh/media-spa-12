<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Favorite;
use Laravel\Scout\Searchable;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[SearchUsingPrefix(['title','title_en'])]
class Blog extends Model implements Viewable
{
    use HasFactory ,Searchable,InteractsWithViews,Rateable;
    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'title_en',
        'group',
        'type',
        'category',
        'text',
        'status'
    ];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'title_en' => $this->title_en,
        ];
    }

    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('status',4)->whereNull('parent_id')->with('replies')->with('user');
    }
    public function favorite()
    {
        return $this->morphMany(Favorite::class,'favoritable');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('image')->with('roles')->with('socials')->with('profile');
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
}
