<?php

namespace App\Interfaces\Services;

use App\Models\BonusAccounts;
use App\Models\User;

interface BonusServiceInterface
{
    static function createAccount(User $user) : BonusAccounts;

    function getBalance() : float;

    function replenish(float $amount) : void;
}
