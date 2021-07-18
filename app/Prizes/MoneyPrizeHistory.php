<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeHistoryInterface;
use App\Models\MoneyPrizes;
use App\Models\User;

class MoneyPrizeHistory implements PrizeHistoryInterface
{
    public static function getHistory(User $user) : array
    {
        return MoneyPrizes::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
    }
}
