<?php

namespace Tests\GameComponents;

use App\Commands\MoneyConvertCommand;
use App\Models\User;
use App\Prizes\MoneyPrize;
use App\Services\BankService;
use App\Services\BonusService;
use Tests\TestCase;
use App\Services\LimitsService;

class MoneyConvertTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_convert()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // create accounts and init limits
        BankService::createAccount($user);
        BonusService::createAccount($user);
        LimitsService::setUserLimits($user);

        $moneyPrise = MoneyPrize::createPrize($user);
        $moneyPrizesHistoryItem = $moneyPrise->applyPrize();

        $bonusService =  new BonusService();
        $moneyConvertCommand = new MoneyConvertCommand($moneyPrizesHistoryItem, $bonusService);

        // act
        $this->assertEquals(true, $moneyConvertCommand->handle());

        $moneyPrizesHistoryItem->refresh();

        $this->assertEquals(true, $moneyPrizesHistoryItem->converted);
    }
}
