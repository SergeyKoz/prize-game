<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BonusAccounts extends Model
{
    protected $table = 'bonus_accounts';

    protected $fillable = [
        'user_id',
        'balance',
    ];
}
