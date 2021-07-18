<?php

namespace App\Prizes;

use App\Interfaces\Prizes\PrizeHistoryInterface;
use App\Models\SubjectPrizes;
use App\Models\User;

class SubjectPrizeHistory implements PrizeHistoryInterface
{
    public static function getHistory(User $user) : array
    {
        return SubjectPrizes::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
    }
}
