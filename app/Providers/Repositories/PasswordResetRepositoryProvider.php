<?php

namespace App\Providers\Repositories;

use App\Repositories\PasswordResetRepository;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class PasswordResetRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PasswordResetRepositoryInterface::class,
            PasswordResetRepository::class
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
