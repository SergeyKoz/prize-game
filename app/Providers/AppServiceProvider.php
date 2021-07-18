<?php

namespace App\Providers;

use App\Interfaces\Services\BankServiceInterface;
use App\Interfaces\Services\BonusServiceInterface;
use App\Interfaces\Services\GameServiceInterface;
use App\Services\BankService;
use App\Services\BonusService;
use App\Services\GameService;
use BaconQrCode\Renderer\Path\Path;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->runningUnitTests()) {
            $this->app->singleton(BonusServiceInterface::class, BonusService::class);
            $this->app->singleton(GameServiceInterface::class, GameService::class);
            $this->app->singleton(BankServiceInterface::class, BankService::class);

//            $this->app->bind(FirewallRepositoryInterface::class, TestFirewallRepository::class);
//            config(['services.firewall.useWhitelist' => false]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view)
        {
//            auth()->user()
            //if (auth()->check()) {
                $this->app->singleton(GameServiceInterface::class, GameService::class);
                //echo print_r(auth()->user());
                //die();
            //}
        });
    }
}
