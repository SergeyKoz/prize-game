<?php

namespace App\Interfaces\Services;

interface GameServiceInterface
{
    function play() : array;

    function getPrizesHistory() : array;
}
