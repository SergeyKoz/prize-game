<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BonusPrizes extends Model
{
    protected $table = 'bonus_prizes';

    protected $fillable = [
        'user_id',
        'amount',
    ];
}
