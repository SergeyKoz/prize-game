<?php

namespace Tests\GameComponents;

use App\Models\User;
use App\Services\GameService;
/*use PHPUnit\Framework\TestCase;*/
use Tests\TestCase;

class GameServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $gameService = new GameService();

        $gameService->play();
        $gameService->play();
        $gameService->play();
        $gameService->play();
        $gameService->play();

        $history = $gameService->getPrizesHistory();

        echo print_r($history);

        //print_r($gameService);
        die();
    }
}
