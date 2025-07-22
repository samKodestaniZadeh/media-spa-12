<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'uuid',
        'transactionId',
        'transaction',
        'price',
        'bank_name',
        'account_name',
        'cart_number',
        'code_p',
        'code_e',
        'date',
        'status',
        'depositable_type',
        'depositable_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function depositable()
    {
        return $this->morphTo();
    }
}
