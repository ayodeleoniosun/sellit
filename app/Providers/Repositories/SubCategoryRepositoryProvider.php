<?php

namespace App\Providers\Repositories;

use App\Repositories\Interfaces\SubCategoryRepositoryInterface;
use App\Repositories\SubCategoryRepository;
use Illuminate\Support\ServiceProvider;

class SubCategoryRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SubCategoryRepositoryInterface::class,
            SubCategoryRepository::class
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
