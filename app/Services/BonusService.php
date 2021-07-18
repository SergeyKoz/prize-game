<?php

namespace App\Services;

use App\Interfaces\Services\BonusServiceInterface;
use App\Models\BonusAccounts;
use App\Models\BonusTransactions;
use App\Models\User;

class BonusService implements BonusServiceInterface
{
    private ?User $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public static function createAccount(User $user) : BonusAccounts
    {
        return BonusAccounts::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
    }

    public function getBalance() : float
    {
        $account = BonusAccounts::where(['user_id' => $this->user->id])->first();

        return $account->balance;
    }

    public function replenish(float $amount) : void
    {
        $account = BonusAccounts::where(['user_id' => $this->user->id])->first();
        $account->balance += $amount;
        $account->save();

        BonusTransactions::create([
            'account_id' => $account->id,
            'amount' => $amount,
            'created_at' => $account->freshTimestamp(),
        ]);
    }
}
