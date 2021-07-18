<?php

namespace App\Interfaces\Prizes;

interface PrizeCommandInterface
{
    function handle() : bool;
}
