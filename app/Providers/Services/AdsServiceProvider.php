<?php

namespace App\Providers\Services;

use App\Contracts\Services\AdsServiceInterface;
use App\Services\AdsService;
use Illuminate\Support\ServiceProvider;

class AdsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AdsServiceInterface::class,
            AdsService::class
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
