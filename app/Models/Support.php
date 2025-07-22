<?php

namespace App\Models;

use App\Models\File;
use App\Models\Menu;
//use App\Models\Support;
use App\Models\User;
use App\Models\Supportable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Support extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'parent_id',
        'user_name',
        'role',
        'destination',
        'menu',
        'recepiant',
        'subject',
        'text',
        'file',
        'status',
        'supportable_type',
        'supportable_id'
        ];

    public function menuable()
    {
        return $this->morphTo();
    }
    public function file ()
    {
        return $this->morphOne(File::class,'fileable');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('image');
    }
    // public function userimage()
    // {
    //     return $this->belongsToMany(File::class,User::class,'id','id','user_id','fileable_id')->
    //     where('fileable_type',User::class);
    // }
    // public function file()
    // {
    //     return $this->belongsTo(File::class);
    // }
    public function replies()
    {
        return $this->hasMany(Support::class, 'parent_id');
    }
    // public function repliesuser()
    // {
    //     return $this->hasOneThrough(User::class,Support::class,'parent_id','id','user_id','user_id');
    // }
    // public function repliesfile()
    // {
    //     return $this->hasOneThrough(File::class,Support::class,'parent_id','user_id','user_id','user_id');
    // }
    public function recepiant()
    {
        return $this->belongsTo(Menu::class, 'recepiant','id');
    }
    public function subject()
    {
        return $this->belongsTo(Menu::class, 'subject','id');
    }
    public function supportables()
    {
        return $this->hasMany(Supportable::class, 'support_id');
    }
}
