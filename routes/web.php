<?php

use App\Commands\MoneyConvertCommand;
use App\Commands\MoneyWithdrawCommand;
use App\Commands\SubjectRefuseCommand;
use App\Interfaces\Services\BankServiceInterface;
use App\Interfaces\Services\BonusServiceInterface;
use App\Interfaces\Services\GameServiceInterface;
use App\Models\MoneyPrizes;
use App\Models\SubjectPrizes;
use App\Services\BankService;
use App\Services\BonusService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/', function (
    GameServiceInterface $gameService, BonusServiceInterface $bonusService, BankServiceInterface $bankService) {

    $history = $gameService->getPrizesHistory();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'balance' => [
            'money' => $bankService->getBalance(auth()->user()->bankAccount),
            'bonus' => $bonusService->getBalance()
        ],
        'moneyPrizes' => $history['money'],
        'bonusPrizes' => $history['bonus'],
        'subjectPrizes' =>  $history['subject'],
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/api/play', function (GameServiceInterface $gameService) {
        return $gameService->play();
    });
    Route::post('/api/withdraw-money/{id}', function ($id, BankService $bankService) {
        $bankAccount = auth()->user()->bankAccount;
        $command = new MoneyWithdrawCommand(MoneyPrizes::find($id), $bankAccount, $bankService);
        return ['result' => $command->handle()];
    });
    Route::post('/api/convert-money/{id}', function ($id, BonusService $bonusService) {
        $command = new MoneyConvertCommand(MoneyPrizes::find($id), $bonusService);
        return ['result' => $command->handle()];
    });
    Route::post('/api/refuse-subject/{id}', function ($id) {
        $command = new SubjectRefuseCommand(SubjectPrizes::find($id));
        return['result' => $command->handle()];
    });
    Route::post('/api/money-balance', function (BankService $bankService) {
        $bankAccount = auth()->user()->bankAccount;
        return $bankService->getBalance($bankAccount);
    });
    Route::post('/api/bonus-balance', function (BonusService $bonusService) {
        return $bonusService->getBalance();
    });
});
