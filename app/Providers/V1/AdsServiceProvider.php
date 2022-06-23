<?php

namespace App\Providers\V1;

use App\Repositories\AdsRepository;
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
            AdsRepository::class,
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
