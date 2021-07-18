<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeHistoryInterface;
use App\Models\BonusPrizes;
use App\Models\User;

class BonusPrizeHistory implements PrizeHistoryInterface
{
    public static function getHistory(User $user) : array
    {
        return BonusPrizes::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
    }
}
