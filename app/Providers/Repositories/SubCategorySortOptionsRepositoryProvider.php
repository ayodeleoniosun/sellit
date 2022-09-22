<?php

namespace App\Providers\Repositories;

use App\Contracts\Repositories\Category\SubCategorySortOptionsRepositoryInterface;
use App\Repositories\Category\SubCategorySortOptionsRepository;
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
