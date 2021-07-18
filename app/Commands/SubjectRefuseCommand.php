<?php

namespace App\Commands;

use App\Interfaces\Prizes\PrizeCommandInterface;
use App\Models\SubjectPrizes;

class SubjectRefuseCommand implements PrizeCommandInterface
{
    private SubjectPrizes $subjectPrizeItem;

    public function __construct(SubjectPrizes $subjectPrizeItem)
    {
        $this->subjectPrizeItem = $subjectPrizeItem;
    }

    public function handle() : bool
    {
        $this->subjectPrizeItem->refused = true;
        $this->subjectPrizeItem->save();

        return true;
    }
}
