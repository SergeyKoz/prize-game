<?php

namespace App\Commands;

use App\Interfaces\Prizes\PrizeCommandInterface;
use App\Models\MoneyPrizes;
use App\Services\BonusService;
use App\Services\Convertor;

class MoneyConvertCommand implements PrizeCommandInterface
{
    private MoneyPrizes $moneyPrizeItem;

    private BonusService $bonusService;

    public function __construct(MoneyPrizes $moneyPrizeItem, BonusService $bonusService)
    {
        $this->moneyPrizeItem = $moneyPrizeItem;
        $this->bonusService = $bonusService;
    }

    public function handle() : bool
    {
        $amount = $this->moneyPrizeItem->amount;
        $bonuses = Convertor::convertMoneyToBonuses($amount);
        $this->bonusService->replenish($bonuses);
        $this->moneyPrizeItem->converted = true;
        $this->moneyPrizeItem->save();

        return true;
    }
}
