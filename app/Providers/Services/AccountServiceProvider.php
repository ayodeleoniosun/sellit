<?php

namespace App\Providers\Services;

use App\Services\AccountService;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AccountServiceInterface::class,
            AccountService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
