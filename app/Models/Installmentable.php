<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installmentable extends Model
{
    protected $table = 'installmentables';
    use HasFactory;
    protected $fillable = [
        'installment_id',
        'installmentable_type',
        'installmentable_id',
    ];
}
