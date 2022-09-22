<?php

namespace App\Providers\Repositories;

use App\Repositories\Interfaces\SubCategorySortOptionsRepositoryInterface;
use App\Repositories\SubCategorySortOptionsRepository;
use Illuminate\Support\ServiceProvider;

class SubCategorySortOptionsRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SubCategorySortOptionsRepositoryInterface::class,
            SubCategorySortOptionsRepository::class
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
