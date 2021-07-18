<?php

namespace App\Console\Commands;

use App\Models\MoneyPrizes;
use App\Services\BankService;
use Illuminate\Console\Command;

class SendMoneyToBankCommand extends Command
{
    const DEFAULT_BATCH_SIZE = 100;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prize-game:send-money-to-bank {--batch= : Batch size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends user\'s money into their bank accounts by batch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $batch = $this->option('batch') ?? self::DEFAULT_BATCH_SIZE;

        $moneyPrises = MoneyPrizes::query()
            ->select('money_prizes.id', 'users.bankAccount', 'money_prizes.amount')
            ->where('converted', false)
            ->where('withdrawn', false)
            ->join('users', 'users.id', '=', 'money_prizes.user_id')
            ->limit($batch)
            ->get()
            ->toArray();

        $bankService = new BankService();

        foreach ($moneyPrises as $item) {
            $bankService->replenish($item['bankAccount'], $item['amount']);

            $moneyPrize = MoneyPrizes::find($item['id']);
            $moneyPrize->withdrawn = true;
            $moneyPrize->save();
        }

        $this->info('Were sent ' . count($moneyPrises) . ' transactions');

        return 0;
    }
}
