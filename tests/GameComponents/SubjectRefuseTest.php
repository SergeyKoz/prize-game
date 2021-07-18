<?php

namespace Tests\GameComponents;

use App\Commands\SubjectRefuseCommand;
use App\Models\User;
use App\Prizes\SubjectPrize;
use Tests\TestCase;
use App\Services\LimitsService;

class SubjectRefuseTest extends TestCase
{
    /**
     * @return void
     **/
    public function test_withdraw()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        LimitsService::setUserLimits($user);

        $subjectPrise = SubjectPrize::createPrize($user);
        $subjectPrizesHistoryItem = $subjectPrise->applyPrize();

        $moneyConvertCommand = new SubjectRefuseCommand($subjectPrizesHistoryItem);

        // act
        $this->assertEquals(true, $moneyConvertCommand->handle());

        $subjectPrizesHistoryItem->refresh();

        $this->assertEquals(true, $subjectPrizesHistoryItem->refused);
    }
}
