<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'national_code',
        'national_id',
        'economical_number',
        'status',
    ];

    public function file()
    {
        return $this->morphOne(File::class,'fileable');
    }
}
