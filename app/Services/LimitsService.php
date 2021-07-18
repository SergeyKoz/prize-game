<?php

namespace App\Services;

use App\Models\PrizeLimits;
use App\Models\User;

class LimitsService
{
    private User $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    static public function setUserLimits(User $user) : void
    {
        $prizesList = config('app.prizes');

        foreach ($prizesList as $key => $item) {
            PrizeLimits::create([
                'user_id' => $user->id,
                'prize' => $key,
                'limit' => $item['limit']
            ]);
        }

    }

    public function getCurrentLimit(string $prize) : int
    {
        $prizeLimits = PrizeLimits::where(['prize' => $prize, 'user_id' => $this->user->id])->first();

        return $prizeLimits->limit;
    }

    public function updateCurrentLimit(string $prize, int $amount) : int
    {
        $prizeLimits = PrizeLimits::where(['prize' => $prize, 'user_id' => $this->user->id])->first();

        $prizeLimits->limit -= $amount;

        $prizeLimits->save();

        return $prizeLimits->limit;
    }

    public function getAllowedAmount($prize, $amount)
    {
        $limit = $this->getCurrentLimit($prize);

        if ($limit !== null) {
            if ($limit === 0) {
                $amount = 0;
            } elseif ($limit > 0 && $amount > $limit) {
                $amount = $limit;
            }
        }

        return $amount;
    }
}
