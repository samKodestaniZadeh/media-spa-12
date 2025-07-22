<?php

namespace App\Models;

use App\Models\File;
use App\Models\Link;
use App\Models\Rate;
use App\Models\Role;
use App\Models\Image;
use App\Models\Sikll;
use App\Models\Social;
use App\Models\Tarahi;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Favorite;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,Rateable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'name',
        'lasst_name',
        'name_show',
        'tel',
        'phone',
        'email',
        'password',
        'status',
        'person',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    public function products()
    {
        return $this->hasMany(Product::class)->with('image');
    }
    public function profile()
    {
        return $this->belongsTo(Profile::class,'id','user_id');
    }
    public function file()
    {
        return $this->morphOne(File::class,'fileable');
    }
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
    public function comments()
    {
        return $this->belongsToMany(Comment::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function ordersProduct()
    {
        return $this->hasMany(Orderable::class,'user_id','id')->where('orderable_type','App\Models\Product');
    }
    public function ordersTarahi()
    {
        return $this->hasMany(Orderable::class,'user_id','id')->where('orderable_type','App\Models\Tarahi');
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','id')->with('orderable');
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }
    public function socials()
    {
        return $this->hasMany(Social::class)->with('link');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function tarahis()
    {
        return $this->hasMany(Tarahi::class);
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class,'id','user_id')->with('file');
    }

    public function sikll()
    {
        return $this->belongsTo(Sikll::class,'id','user_id');
    }

    public function siklls()
    {
        return $this->hasMany(Sikll::class,'user_id','id');
    }
    public function rates()
    {
        return $this->morphMany(Rate::class,'rateable');
    }
    public function session()
    {
        return $this->belongsTo(Session::class,'id','user_id');
    }

}
