<?php

namespace App\Commands;

use App\Interfaces\Prizes\PrizeCommandInterface;
use App\Models\MoneyPrizes;
use App\Services\BankService;

class MoneyWithdrawCommand implements PrizeCommandInterface
{
    private MoneyPrizes $moneyPrizeItem;

    private BankService $bankService;

    private string $bankAccount;

    public function __construct(MoneyPrizes $moneyPrizeItem, string $bankAccount, BankService $bankService)
    {
        $this->moneyPrizeItem = $moneyPrizeItem;
        $this->bankService = $bankService;
        $this->bankAccount = $bankAccount;
    }

    public function handle() : bool
    {
        $amount = $this->moneyPrizeItem->amount;
        $this->bankService->replenish($this->bankAccount, $amount);
        $this->moneyPrizeItem->withdrawn = true;
        $this->moneyPrizeItem->save();
        return true;
    }
}
