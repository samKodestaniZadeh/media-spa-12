<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'status',
        'fileable_type',
        'fileable_id'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
}
