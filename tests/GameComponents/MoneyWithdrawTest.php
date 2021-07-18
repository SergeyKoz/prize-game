<?php

namespace Tests\GameComponents;

use App\Commands\MoneyWithdrawCommand;
use App\Models\User;
use App\Prizes\MoneyPrize;
use App\Services\BankService;
use App\Services\BonusService;
use Tests\TestCase;

use App\Services\LimitsService;

class MoneyWithdrawTest extends TestCase
{
    /**
     * @return void
     */
    public function test_withdraw()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // create accounts and init limits
        BankService::createAccount($user);
        BonusService::createAccount($user);
        LimitsService::setUserLimits($user);

        $moneyPrise = MoneyPrize::createPrize($user);
        $moneyPrizesHistoryItem = $moneyPrise->applyPrize();

        $bankService =  new BankService();
        $moneyWithdrawCommand = new MoneyWithdrawCommand($moneyPrizesHistoryItem, $user->bankAccount, $bankService);

        // act
        $this->assertEquals(true, $moneyWithdrawCommand->handle());

        $moneyPrizesHistoryItem->refresh();

        $this->assertEquals(true, $moneyPrizesHistoryItem->withdrawn);
    }
}
