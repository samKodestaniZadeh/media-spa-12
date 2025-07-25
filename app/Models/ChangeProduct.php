<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'version',
        'text',
    ];
}
