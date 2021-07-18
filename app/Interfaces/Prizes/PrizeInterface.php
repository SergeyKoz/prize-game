<?php

namespace App\Interfaces\Prizes;

use App\Models\User;

interface PrizeInterface
{
    static function createPrize(User $user);

    function applyPrize();
}
