<?php

namespace App\Interfaces\Prizes;

use App\Models\User;

interface PrizeHistoryInterface
{
    static function getHistory(User $user);
}
