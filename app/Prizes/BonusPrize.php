<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeInterface;
use App\Models\BonusPrizes;
use App\Models\User;
use App\Services\BonusService;

class BonusPrize implements PrizeInterface
{
    const MIN_BONUS = 10;
    const MAX_BONUS = 100;

    private float $amount;

    private User $user;

    private BonusService $bonusService;

    public function __construct(float $amount, User $user, BonusService $bonusService)
    {
        $this->amount = $amount;
        $this->user = $user;
        $this->bonusService = $bonusService;
    }

    /**
     * @throws \Exception
     */
    public static function createPrize(User $user)
    {
        $amount = random_int(self::MIN_BONUS, self::MAX_BONUS);

        $bonusService = new BonusService();

        return new self($amount, $user, $bonusService);
    }

    public function applyPrize() : BonusPrizes
    {
        $bonusPrizes = BonusPrizes::create([
            'user_id' => $this->user->id,
            'amount' => $this->amount,
        ]);

        $this->bonusService->replenish($this->amount);

        return $bonusPrizes;
    }
}
