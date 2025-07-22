<?php

namespace App\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected  $fillable = [
        'user_id',
        // 'name',
        // 'economical_number',
        // 'national_number',
        // 'postal_code',
        // 'phone',
        // 'mobile',
        // 'ostan',
        // 'shahr',
        // 'address',
        'tax',
        'complications',
        'comison',
        // 'email',
        // 'telegram',
        // 'instagram',
        // 'link',
        'comison_designer',
        'design_damage',
        'job',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
