<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PrizeLimits extends Model
{
    protected $table = 'prize_limits';

    protected $fillable = [
        'user_id',
        'prize',
        'limit',
    ];
}
