<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routeable extends Model
{
    use HasFactory;

    protected $table = 'routeables';
    protected $fillable = [
        'route_id',
        'routeable_type',
        'routeable_id',
    ];
}
