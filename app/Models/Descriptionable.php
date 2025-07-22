<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descriptionable extends Model
{
    use HasFactory;
    protected $table = 'descriptionables';
    protected $fillable = [
        'description_id',
        'descriptionable_type',
        'descriptionable_id',
    ];
}
