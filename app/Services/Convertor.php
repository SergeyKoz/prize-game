<?php

namespace App\Services;

class Convertor
{
    const MONEY_TO_BONUSES_RATE = 2;

    public static function convertMoneyToBonuses(int $amount) : float
    {
        return $amount * self::MONEY_TO_BONUSES_RATE;
    }
}
