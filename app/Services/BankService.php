<?php

namespace App\Services;

use App\Interfaces\Services\BankServiceInterface;
use App\Models\BankAccounts;
use App\Models\BankTransactions;
use App\Models\User;

class BankService implements BankServiceInterface
{
    static public function createAccount(User $user) : BankAccounts
    {
        return BankAccounts::create([
            'account' => $user->bankAccount,
            'balance' => 0,
        ]);
    }
    public function getBalance(int $account) : float
    {
        $account = BankAccounts::where(['account' => $account])->first();

        return $account->balance;
    }

    public function replenish(int $account, float $amount) : void
    {
        $account = BankAccounts::where(['account' => $account])->first();
        $account->balance += $amount;
        $account->save();

        BankTransactions::create([
            'account_id' => $account->id,
            'amount' => $amount,
            'created_at' => $account->freshTimestamp(),
        ]);
    }
}
