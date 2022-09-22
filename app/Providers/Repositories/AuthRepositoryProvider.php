<?php

namespace App\Providers\Repositories;

use App\Entities\Repositories\User\AuthRepositoryInterface;
use App\Repositories\User\AuthRepository;
use Illuminate\Support\ServiceProvider;

class AuthRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
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
