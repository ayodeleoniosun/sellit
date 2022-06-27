<?php

namespace App\Providers\Repositories;

use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AccountRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AccountRepositoryInterface::class,
            AccountRepository::class
        );
    }

    /**
     * Bootstrap any application service.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
