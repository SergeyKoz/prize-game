<?php

namespace App\Services;

use App\Interfaces\Services\GameServiceInterface;
use App\Models\User;

class GameService implements GameServiceInterface
{
    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function play() : array
    {
        $prizesList = config('app.prizes');

        $prizes = [];

        foreach ($prizesList as $key => $item) {
            $prize = $item['prizeClass']::createPrize($this->user);

            if ($prize) {
                $prizes[$key] = $prize;
            }
        }

        $key = array_rand($prizes);

        return [$key => $prizes[$key]->applyPrize()];
    }

    public function getPrizesHistory() : array
    {
        $prizesList = config('app.prizes');
        $prizeHistory = [];

        foreach ($prizesList as $key => $item) {
            $prizeHistory[$key] = $item['prizeHistoryClass']::getHistory($this->user);
        }

        return $prizeHistory;
    }
}
