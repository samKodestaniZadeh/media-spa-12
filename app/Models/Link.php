<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'tag',
        'link',
        'linkable_type',
        'linkable_id',
        'status'
    ];
    public function linkable()
    {
        return $this->morphTo();
    }
}
