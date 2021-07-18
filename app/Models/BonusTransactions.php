<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BonusTransactions extends Model
{
    protected $table = 'bonus_transactions';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'amount',
        'created_at',
    ];
}
