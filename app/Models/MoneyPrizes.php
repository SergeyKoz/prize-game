<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MoneyPrizes extends Model
{
    protected $table = 'money_prizes';

    protected $fillable = [
        'user_id',
        'amount',
    ];
}
