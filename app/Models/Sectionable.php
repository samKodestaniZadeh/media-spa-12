<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sectionable extends Model
{
    use HasFactory;

    protected $table = 'sectionables';
    protected $fillable = [
        'section_id',
        'sectionable_type',
        'sectionable_id',
    ];
}
