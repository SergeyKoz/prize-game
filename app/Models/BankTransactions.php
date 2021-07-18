<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BankTransactions extends Model
{
    protected $table = 'bank_transactions';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'amount',
        'created_at',
    ];
}
