<?php

namespace Tests\GameComponents;

use App\Models\User;
use App\Services\BankService;
use App\Services\BonusService;
use App\Services\GameService;
use Tests\TestCase;

use App\Services\LimitsService;

class GameServiceTest extends TestCase
{
    /**
      * @return void
     */
    public function test_game()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $plays = 5;

        // create accounts and init limits
        BankService::createAccount($user);
        BonusService::createAccount($user);
        LimitsService::setUserLimits($user);

        $gameService = new GameService();

        for ($i = 0; $i < $plays; $i++) {
            $gameService->play();
        }

        $history = $gameService->getPrizesHistory();

        $prizes = [];

        foreach ($history as $items) {
            $prizes = array_merge($prizes, $items);
        }

        $this->assertCount($plays, $prizes);
    }
}
