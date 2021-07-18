<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeInterface;
use App\Models\MoneyPrizes;
use App\Models\User;
use App\Services\LimitsService;
use Exception;

class MoneyPrize implements PrizeInterface
{
    const MIN_AMOUNT = 10;
    const MAX_AMOUNT = 100;

    private float $amount;

    private User $user;

    private LimitsService $limitsService;

    public function __construct(float $amount, User $user, LimitsService $limitsService)
    {
        $this->amount = $amount;
        $this->user = $user;
        $this->limitsService = $limitsService;
    }

    /**
     * @throws Exception
     */
    public static function createPrize(User $user): ?MoneyPrize
    {
        $amount = random_int(self::MIN_AMOUNT, self::MAX_AMOUNT);

        $limitsService = new LimitsService($user);

        $amount = $limitsService->getAllowedAmount('money', $amount);

        if ($amount === 0) {
            return null;
        }

        return new self($amount, $user, $limitsService);
    }

    /**
     * @throws Exception
     */
    public function applyPrize() : MoneyPrizes
    {
        $amount = $this->limitsService->getAllowedAmount('money', $this->amount);

        if ($amount > 0) {
            $moneyPrize = MoneyPrizes::create(
                [
                    'user_id' => $this->user->id,
                    'amount' => $amount,
                ]
            );

            $this->limitsService->updateCurrentLimit('money', $amount);

            return $moneyPrize;
        } else {
            throw new Exception('Limit exceeded');
        }
    }
}
