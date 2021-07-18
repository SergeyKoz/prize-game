<?php

namespace App\Interfaces\Services;

use App\Models\BankAccounts;
use App\Models\User;

interface BankServiceInterface
{
    static function createAccount(User $user) : BankAccounts;

    function getBalance(int $account) : float;

    function replenish(int $account, float $amount) : void;
}
